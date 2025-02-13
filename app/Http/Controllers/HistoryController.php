<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Organization;
use App\Models\SellHistory;
use App\Models\Purchase_History;
use App\Models\Sell;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HistoryController extends Controller
{
    public function history(Request $request)
    {
        // dd('helo');
        $brands = Brand::all();
        $companies = Organization::all();
        $products = Product::all();



        // $stocks = DB::table('batches')
        //     ->join('products', 'batches.product_id', '=', 'products.id')
        //     ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
        //     ->select(
        //         'products.sku',
        //         'batches.batch_number as batch_no',
        //         'batches.buy_price',
        //         DB::raw('COUNT(cartons.id) as cartons'),
        //         DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
        //         DB::raw('SUM(cartons.missing_items) as missing_items'),
        //         'batches.id as batch_id'
        //     )
        //     ->groupBy('batches.id', 'products.sku', 'batches.batch_number', 'batches.buy_price')
        //     ->orderBy('batches.id', 'DESC')
        //     ->get();


        return view('admin.purchaseHistory', compact('companies', 'products', 'brands'));
    }

    public function getHistory(Request $request)
    {
        try {
            $organization = Organization::where('id', $request->company)->first();
            $selectedDate = $request->selectedDate;
            $productId = $request->productId;
            $brandId = $request->brandId;
    
            $products = Product::with('brand')->get();
    
            config(['database.connections.pgsql.database' => $organization->name]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();
    
            $sellCounterSubquery = DB::table('sell_counter')
                ->select('batch_id', DB::raw('SUM(provided_no_of_cartons) as total_provided'))
                ->whereDate('created_at', $selectedDate)
                ->groupBy('batch_id');
    
            $query = DB::table('batches')
                ->leftJoinSub($sellCounterSubquery, 'sc', function ($join) {
                    $join->on('batches.id', '=', 'sc.batch_id');
                })
                ->select(
                    'batches.product_id',
                    'batches.unit',
                    'batches.no_of_units',
                    DB::raw('SUM(batches.quantity) as total_quantity'),
                    DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
                     DB::raw('SUM(batches.buy_price) as total_buy_price'),
                    DB::raw('MAX(batches.created_at) as first_created_at'),
                    DB::raw('MAX(batches.invoice_no) as invoice'),
                    DB::raw('MAX(batches.expiry_date) as expiry_date'),
                    DB::raw('SUM(batches.quantity) + COALESCE(SUM(sc.total_provided), 0) as previous_total_no_of_quantity'),
                )
                ->whereDate('batches.created_at', $selectedDate)
                ->groupBy('batches.product_id', 'batches.unit', 'batches.no_of_units')
                ->orderBy('product_id', 'ASC');
    
            $stocksList = $query->get();

            if ($productId) {
                $stocksList = $stocksList->filter(fn($stock) => $stock->product_id == $productId);
            }
    
            if ($brandId) {
                $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
                    $product = $products->firstWhere('id', $stock->product_id);
                    return $product && $product->brand_id == $brandId;
                });
            }
    
            $groupedData = $stocksList->groupBy(['product_id'])->map(function ($stocks, $productId) use ($products) {
                return $stocks->map(function ($stock) use ($products, $productId) {
                    $product = $products->firstWhere('id', $productId);
                    return [
                        'product_id' => $productId,
                        'product_name' => $product->name ?? null,
                         'total_buy_price' => $stock->total_buy_price ?? null,
                        'brand_name' => $product->brand->name ?? null,
                        'unit' => $product->unit,
                        'no_of_units' => $stock->no_of_units,
                        'total_quantity' => $stock->total_quantity,
                        'total_no_of_unit' => $stock->total_no_of_unit,
                        'status' => $product->status ?? null,
                        'expiry_date' => $stock->expiry_date ?? null,
                        'created_at' => $stock->first_created_at ?? null,
                        'invoice' => $stock->invoice ?? null,
                        'previous_total_no_of_quantity' => $stock->previous_total_no_of_quantity ?? null
                    ];
                });
            });
    
            return response()->json(['data' => $groupedData]);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purchaseHistoryShow($id,$encodedCreatedAt,$noOfcartoon)
    {
        
        $product = Product::where('id', $id)->first();
        $brand = Brand::where('id', $product->brand_id)->first();
        $createdAt = base64_decode($encodedCreatedAt);
        // $organization = Organization::select('id','name')->where('name',3)->first();

        setDatabaseConnection();

        $batchData = DB::table('batches')
        ->leftjoin('sell','sell.batch_no','=','batches.batch_number')
            ->select(
                'sell.*',
                'batches.id as batch_id',
                'batches.unit',
                'batches.batch_number',
                'batches.product_id',
                'batches.manufacturing_date',
                'batches.expiry_date',
                'batches.base_price',
                'batches.exchange_rate',
                'batches.buy_price',
                'batches.notes',
                'batches.no_of_units',
                'batches.quantity',
                'batches.created_at',
                'batches.updated_at',
                'batches.invoice_no',
            )
            ->where('product_id', $id)
            ->where(['batches.no_of_units' => $noOfcartoon])
            ->whereRaw('DATE(batches.created_at) = ?', [$createdAt])
            ->get();

        // Check if batch data is empty
        if ($batchData->isEmpty()) {
            return redirect()->route('stock.list')->with('error', 'No stock batches found for the selected product.');
        }

        // Group cartons under their respective batches and then by product
        $groupedData = $batchData->groupBy('product_id')->map(function ($items) use ($brand) {
            $product = $items->first(); // Common product details

            return [
                'product_id' => $product->product_id,
                'brand_name' => $brand->name,
                'batches' => $items->groupBy('batch_id')->map(function ($batchItems) {
                    $batch = $batchItems->first(); // Common batch details for a specific batch

                    return [
                        'batch_id' => $batch->batch_id,
                        'unit' => $batch->unit,
                        'batch_number' => $batch->batch_number,
                        'manufacturing_date' => $batch->manufacturing_date,
                        'expiry_date' => $batch->expiry_date,
                        'base_price' => $batch->base_price,
                        'exchange_rate' => $batch->exchange_rate,
                        'buy_price' => $batch->buy_price,
                        'no_of_units' => $batch->no_of_units,
                        'quantity' => $batch->quantity,
                        'notes' => $batch->notes,
                        'created_at' => $batch->created_at,
                        'updated_at' => $batch->updated_at,
                        'hospital_price' => $batch->hospital_price,
                        'wholesale_price' => $batch->wholesale_price,
                        'retail_price' => $batch->retail_price,
                        'invoice_no' => $batch->invoice_no,

                    ];
                })->values(),
            ];
        });

        if (auth()->user()->role == 1) {
            return view('admin.purchaseHistoryEdit', compact('groupedData', 'brand'));
        }
        // dd($groupedData);
        return view('admin.edit', compact('groupedData', 'brand'));
    }

    public function detailHistory(Request $request, $productId, $encodedCreatedAt,$noOfcartoon)
    {
        $product = Product::find($productId);
        $brand = Brand::find($product->brand_id);
         $createdAt = base64_decode($encodedCreatedAt);
        //  dd($noOfcartoon);

        $databaseName = Session::get('db_name');

        if (!$databaseName) {
            return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
        }

        config(['database.connections.pgsql.database' => $databaseName]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        // dd($invoice);
        $data = DB::table('batches')
        ->leftJoin('sell_counter', 'batches.id', '=', 'sell_counter.batch_id')
        ->leftJoin('sell','batches.batch_number','=','sell.batch_no')
        ->select(
            'batches.batch_number',
            'batches.product_id',
            'batches.brand_id',
            'batches.unit',
            'batches.base_price',
            'batches.buy_price',
            'batches.quantity as batch_quantity',
            'batches.no_of_units',
            'batches.manufacturing_date',
            'batches.invoice_no',
            'batches.created_at',
            'batches.expiry_date',
            'batches.exchange_rate',
            'batches.notes',
            'sell_counter.price',
            'sell_counter.customer_type',
            'sell.retail_price',
            'sell.wholesale_price',
            'sell.hospital_price',
            DB::raw('(batches.quantity + COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as purchase_quantity'),
            DB::raw('COALESCE(SUM(sell_counter.provided_no_of_cartons), 0) as sold_cartons'),
            DB::raw('(batches.quantity - COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as remaining_quantity'),
        )
        // ->where(['batches.invoice_no'=> $invoice])
        ->where(['batches.no_of_units' => $noOfcartoon])
        ->where(['batches.product_id'=> $productId])
        ->whereRaw('DATE(batches.created_at) = ?', [$createdAt])
        ->groupBy(
            'batches.id',
            'batches.batch_number',
            'batches.product_id',
            'batches.brand_id',
            'batches.unit',
            'batches.no_of_units',
            'batches.base_price',
            'batches.buy_price',
            'batches.quantity',
            'batches.invoice_no',
            'batches.created_at',
            'batches.manufacturing_date',
            'batches.expiry_date',
            'batches.exchange_rate',
            'batches.notes',
            'sell_counter.price',
            'sell_counter.customer_type',
            'sell.retail_price',
            'sell.wholesale_price',
            'sell.hospital_price',
        )
        ->get();

        // dd($data);

        return view('admin.purchaseHistoryDetails', compact('data','product','brand','createdAt'));
    }
    


    public function sellHistory(Request $request)
    {
        $companies = Organization::all();
        return view('admin.sellHistory', compact('companies'));
    }

    public function getSellHistory(Request $request)
    {
        try {
            if(auth()->user()->role == 1){
                $organization = Organization::where('id', $request->company)->first();
            }else{
                $organization = Organization::where('name', $request->company)->first();         
            }
            $selectedDate = $request->selectedDate;
            $productId = $request->productId;
            $brandId = $request->brandId;
        
            $products = Product::with('brand')->get();
        
            config(['database.connections.pgsql.database' => $organization->name]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();
        
            $sellCounterSubquery = DB::table('sell_counter')
                ->select('batch_id','order_id','status','deleted_at', DB::raw('SUM(provided_no_of_cartons) as total_provided'))
                ->whereDate('created_at', $selectedDate)
                ->where('sell_counter.deleted_at',null)
                ->groupBy('batch_id','order_id','deleted_at','status');
        
            $query = DB::table('batches')
                ->JoinSub($sellCounterSubquery, 'sc', function ($join) {
                    $join->on('batches.id', '=', 'sc.batch_id');
                })
                ->select(
                    'batches.product_id',
                    'batches.unit',
                    'sc.order_id',
                    'sc.status as approve_status',
                    DB::raw('SUM(batches.quantity) as total_quantity'),
                    DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
                    DB::raw('SUM(batches.buy_price) as total_buy_price'),
                    DB::raw('MAX(batches.created_at) as first_created_at'),
                    DB::raw('MAX(batches.invoice_no) as invoice'),
                    DB::raw('MAX(batches.expiry_date) as expiry_date'),
                    DB::raw('SUM(batches.quantity) + COALESCE(SUM(sc.total_provided), 0) as previous_total_no_of_quantity'),
                )
                ->groupBy('batches.product_id', 'batches.unit', 'sc.order_id','sc.status')
                ->orderBy('product_id', 'ASC');
        
            $stocksList = $query->get();
        
            if ($productId) {
                $stocksList = $stocksList->filter(function ($stock) use ($productId) {
                    return $stock->product_id == $productId;
                });
            }
        
            if ($brandId) {
                $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
                    $product = $products->firstWhere('id', $stock->product_id);
                    return $product && $product->brand_id == $brandId;
                });
            }
        
            // Modified this part to maintain separate entries for different order IDs
            $groupedData = $stocksList->map(function ($stock) use ($products) {
                $product = $products->firstWhere('id', $stock->product_id);
                return [
                    'product_id' => $stock->product_id,
                    'product_name' => $product->name ?? null,
                    'total_buy_price' => $stock->total_buy_price,
                    'brand_name' => $product->brand->name ?? null,
                    'unit' => $product->unit ?? null,
                    'total_quantity' => $stock->total_quantity,
                    'total_no_of_unit' => $stock->total_no_of_unit,
                    'status' => $product->status ?? null,
                    'expiry_date' => $stock->expiry_date ?? null,
                    'created_at' => $stock->first_created_at ?? null,
                    'invoice' => $stock->invoice ?? null,
                    'order_id' => $stock->order_id ?? null,
                    'approve_status' => $stock->approve_status,
                    'previous_total_no_of_quantity' => $stock->previous_total_no_of_quantity
                ];
            });
        
            return response()->json(['data' => $groupedData]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function historyProducts(Request $request)
    {
        try {
            $organization = Organization::where('id', '=', $request->company)->first();
            // config(['database.connections.pgsql.database' => $organization->name]);
            // DB::purge('pgsql');
            // DB::connection('pgsql')->getPdo();
            // dd($organization);
            $products = Product::select('id', 'name')->get();


            return response()->json(['products' => $products]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sellHistoryShow(Request $request, $productId, $createdAt)
    {

    }

    public function saleDetailHistory(Request $request, $productId, $encodedCreatedAt, $orderId)
    {
       
        $product = Product::find($productId);
        $brand = Brand::find($product->brand_id);
         $createdAt = base64_decode($encodedCreatedAt);
        // dd($encodedCreatedAt);

        $databaseName = Session::get('db_name');

        if (!$databaseName) {
            return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
        }

        config(['database.connections.pgsql.database' => $databaseName]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        // dd($invoice);
        $data = DB::table('batches')
        ->leftJoin('sell_counter', 'batches.id', '=', 'sell_counter.batch_id')
        ->leftJoin('sell','batches.batch_number','=','sell.batch_no')
        ->select(
            'batches.batch_number',
            'batches.product_id',
            'batches.brand_id',
            'batches.unit',
            'batches.base_price',
            'batches.buy_price',
            'batches.quantity as batch_quantity',
            'batches.no_of_units',
            'batches.manufacturing_date',
            'batches.invoice_no',
            'batches.created_at',
            'batches.expiry_date',
            'batches.exchange_rate',
            'batches.notes',
            'sell_counter.price',
            'sell_counter.customer_type',
            'sell.retail_price',
            'sell.wholesale_price',
            'sell.hospital_price',
            'sell_counter.order_id',
            DB::raw('(batches.quantity + COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as purchase_quantity'),
            DB::raw('COALESCE(SUM(sell_counter.provided_no_of_cartons), 0) as sold_cartons'),
            DB::raw('(batches.quantity - COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as remaining_quantity'),
        )
        // ->where(['batches.invoice_no'=> $invoice])
        ->where(['sell_counter.order_id' => $orderId])
        ->where(['batches.product_id'=> $productId])
        ->whereRaw('DATE(batches.created_at) = ?', [$createdAt])
        ->groupBy(
            'batches.id',
            'batches.batch_number',
            'batches.product_id',
            'batches.brand_id',
            'batches.unit',
            'batches.no_of_units',
            'batches.base_price',
            'batches.buy_price',
            'batches.quantity',
            'batches.invoice_no',
            'batches.created_at',
            'batches.manufacturing_date',
            'batches.expiry_date',
            'batches.exchange_rate',
            'batches.notes',
            'sell_counter.price',
            'sell_counter.customer_type',
            'sell.retail_price',
            'sell.wholesale_price',
            'sell.hospital_price',
            'sell_counter.order_id'
        )
        ->get();

        // dd($data);

        return view('admin.purchaseHistoryDetails', compact('data','product','brand','createdAt'));
    }
}
