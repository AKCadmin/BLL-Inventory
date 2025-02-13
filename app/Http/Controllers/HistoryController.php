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

    // public function getHistory(Request $request)
    // {

    //     if($request->company){
    //     $organization = Organization::where('id', '=', $request->company)->first();
    //     $brand = Brand::where('id', '=', $request->brandName)->first();

    //     $databaseName = Session::get('db_name');
    //     $selectedDate = $request->selectedDate;

    //     config(['database.connections.pgsql.database' => $organization->name]);
    //     DB::purge('pgsql');
    //     DB::connection('pgsql')->getPdo();
    //     $query = DB::table('batches')
    //         ->join('products', 'batches.product_id', '=', 'products.id')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         ->select(
    //             'batches.batch_number as batch_no',
    //             'batches.buy_price',
    //             DB::raw('COUNT(cartons.id) as cartons'),
    //             DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
    //             DB::raw('SUM(cartons.missing_items) as missing_items'),
    //             'batches.id as batch_id'
    //         )
    //         ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price')
    //         ->orderBy('batches.id', 'DESC');

    //     // Apply filter if productId is provided
    //     if ($request->has('productId') && !empty($request->productId)) {
    //         $query->where('batches.product_id', $request->productId);
    //     }

    //     if (!empty($selectedDate)) {
    //         $query->where(function ($q) use ($selectedDate) {
    //             $q->whereDate('batches.manufacturing_date', '<=', $selectedDate)
    //               ->whereDate('batches.expiry_date', '>=', $selectedDate);
    //         });
    //     }

    //     $stocks = $query->get();
    //     }else{
    //         $stocks = [];
    //     }

    //     // dd($stocks);

    //     // $history = Purchase_History::all();

    //     // if (!$history) {
    //     //     return response()->json(['success' => false, 'message' => 'History not found.'], 404);
    //     // }

    //     // // Decode the details field
    //     // $historyDetails = json_decode($history->details, true);

    //     // if (!$historyDetails) {
    //     //     return response()->json(['success' => false, 'message' => 'Invalid history details format.'], 422);
    //     // }

    //     // // Format the response data
    //     // $response = [
    //     //     'id' => $historyDetails['batch']['id'] ?? null,
    //     //     'sku' => $historyDetails['product']['sku'] ?? null,
    //     //     'name' => $historyDetails['product']['name'] ?? null,
    //     //     'description' => $historyDetails['product']['description'] ?? null,
    //     //     'company_id' => $historyDetails['product']['company_id'] ?? null,
    //     //     'status' => $historyDetails['product']['status'] ?? null,
    //     //     'created_at' => $historyDetails['batch']['created_at'] ?? null,
    //     //     'updated_at' => $historyDetails['batch']['updated_at'] ?? null,
    //     //     'batch_number' => $historyDetails['batch']['batch_number'] ?? null,
    //     //     'product_id' => $historyDetails['batch']['product_id'] ?? null,
    //     //     'manufacturing_date' => $historyDetails['batch']['manufacturing_date'] ?? null,
    //     //     'expiry_date' => $historyDetails['batch']['expiry_date'] ?? null,
    //     //     'base_price' => $historyDetails['batch']['base_price'] ?? null,
    //     //     'exchange_rate' => $historyDetails['batch']['exchange_rate'] ?? null,
    //     //     'buy_price' => $historyDetails['batch']['buy_price'] ?? null,
    //     //     'notes' => $historyDetails['batch']['notes'] ?? null,
    //     //     'cartons' => collect($historyDetails['cartons'] ?? [])->map(function ($carton) {
    //     //         return [
    //     //             'carton_number' => $carton['carton_number'] ?? null,
    //     //             'no_of_items_inside' => $carton['no_of_items_inside'] ?? null,
    //     //             'missing_items' => $carton['missing_items'] ?? null,
    //     //         ];
    //     //     }),
    //     // ];


    //     return response()->json(['data' => $stocks]);
    // }

    // public function getHistory(Request $request)
    // {
    //     try {

    //         $organization = Organization::where('id', '=', $request->company)->first();
    //         $selectedDate = $request->selectedDate;
    //         $isToday = $selectedDate === now()->toDateString();
    //         $productId = $request->productId;
    //         $brandId = $request->brandId;

    //         // Get all products with their brands
    //         $products = Product::with('brand')->get();

    //         setDatabaseConnectionForOrganization($organization->name);
    //         // Switch database to the organization's database
    //         // config(['database.connections.pgsql.database' => $organization->name]);
    //         // DB::purge('pgsql');
    //         // DB::connection('pgsql')->getPdo();

    //         // Build the query for batches and cartons
    //         $query = DB::table('batches')
    //             ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //             ->leftJoinSub(
    //                 DB::table('sell_carton')
    //                     ->select(
    //                         'sell_carton.carton_id',
    //                         DB::raw('SUM(sell_carton.no_of_items_sell) as total_sold_items')
    //                     )
    //                     ->whereDate('sell_carton.created_at', '>', $selectedDate)
    //                     ->groupBy('sell_carton.carton_id'),
    //                 'sell_carton',
    //                 'cartons.id',
    //                 '=',
    //                 'sell_carton.carton_id'
    //             )
    //             ->select(
    //                 'batches.batch_number as batch_no',
    //                 'batches.buy_price',
    //                 'batches.product_id',
    //                 DB::raw('COUNT(cartons.id) as cartons'),
    //                 DB::raw('COUNT(batches.id) as totalBatches'),
    //                 DB::raw('COALESCE(SUM(cartons.no_of_items_inside), 0) as total_items'),
    //                 DB::raw('SUM(cartons.missing_items) as missing_items'),
    //                 DB::raw('COALESCE(SUM(sell_carton.total_sold_items), 0) as sold_items'),
    //                 DB::raw('SUM(cartons.no_of_items_inside) + COALESCE(SUM(sell_carton.total_sold_items), 0) as available_items'),
    //                 'batches.id as batch_id'
    //             )
    //             ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'batches.product_id')
    //             ->orderBy('batches.id', 'DESC');

    //         // Execute the query and get the stocks list
    //         $stocksList = $query->get();

    //         // Filter by productId if provided
    //         if ($productId) {
    //             $stocksList = $stocksList->filter(function ($stock) use ($productId) {
    //                 return $stock->product_id == $productId;
    //             });
    //         }

    //         if ($brandId) {
    //             $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
    //                 $product = $products->firstWhere('id', $stock->product_id);
    //                 return $product && $product->brand_id == $brandId;
    //             });
    //         }

    //         // Match and map the stocks with the product details
    //         $matchingStocks = $stocksList->filter(function ($stock) use ($products) {
    //             return $products->contains('id', $stock->product_id);
    //         });

    //         $stocks = $matchingStocks->map(function ($stocksList) use ($products) {
    //             $product = $products->firstWhere('id', $stocksList->product_id);
    //             return [
    //                 'product_id' => $stocksList->product_id,
    //                 'brand_name' => $product->brand ? $product->brand->name : null,
    //                 'product_name' => $product ? $product->name : null,
    //                 'batch_no' => $stocksList->batch_no,
    //                 'buy_price' => $stocksList->buy_price,
    //                 'cartons' => $stocksList->cartons,
    //                 'total_batches' => $stocksList->totalBatches ?? null,
    //                 'total_items' => $stocksList->total_items,
    //                 'missing_items' => $stocksList->missing_items,
    //                 'sold_items' => $stocksList->sold_items,
    //                 'available_items' => $stocksList->available_items,
    //                 'batch_id' => $stocksList->batch_id,
    //                 'status' => $product->status ?? null,
    //             ];
    //         });

    //         return response()->json(['data' => $stocks]);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function getHistory(Request $request)
    // {
    //     try {
    //         $organization = Organization::where('id', '=', $request->company)->first();
    //         $selectedDate = $request->selectedDate;
    //         $productId = $request->productId;
    //         $brandId = $request->brandId;

    //         // Get all products with their brands
    //         $products = Product::with('brand')->get();

    //         setDatabaseConnectionForOrganization($organization->name);
    //         $excludedBatchIds = DB::table('sell_counter')
    //         ->pluck('batch_id');
    //         // Build the query for batches and cartons
    //         $query = DB::table('batches')
    //             ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //             ->leftJoinSub(
    //                 DB::table('sell_carton')
    //                     ->select(
    //                         'sell_carton.carton_id',
    //                         DB::raw('SUM(sell_carton.no_of_items_sell) as total_sold_items')
    //                     )
    //                     ->whereDate('sell_carton.created_at', '>', $selectedDate)
    //                     ->groupBy('sell_carton.carton_id'),
    //                 'sell_carton',
    //                 'cartons.id',
    //                 '=',
    //                 'sell_carton.carton_id'
    //             )
    //             ->select(
    //                 'batches.batch_number as batch_no',
    //                 'batches.buy_price',
    //                 'batches.product_id',
    //                 'batches.created_at',
    //                 DB::raw('COUNT(cartons.id) as cartons'),
    //                 DB::raw('COALESCE(SUM(cartons.no_of_items_inside), 0) as total_items'),
    //                 DB::raw('SUM(cartons.missing_items) as missing_items'),
    //                 DB::raw('COALESCE(SUM(sell_carton.total_sold_items), 0) as sold_items'),
    //                 DB::raw('SUM(cartons.no_of_items_inside) + COALESCE(SUM(sell_carton.total_sold_items), 0) as available_items'),
    //                 'batches.id as batch_id'
    //             )
    //             ->whereNotIn('batches.id', $excludedBatchIds)
    //             ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'batches.product_id','batches.created_at')
    //             ->orderBy('batches.id', 'DESC');

    //         $stocksList = $query->get();

    //         // Filter by productId if provided
    //         if ($productId) {
    //             $stocksList = $stocksList->filter(fn($stock) => $stock->product_id == $productId);
    //         }

    //         // Filter by brandId if provided
    //         if ($brandId) {
    //             $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
    //                 $product = $products->firstWhere('id', $stock->product_id);
    //                 return $product && $product->brand_id == $brandId;
    //             });
    //         }

    //         // Group data by product_id
    //         $groupedData = $stocksList->groupBy('product_id')->map(function ($stocks, $productId) use ($products) {
    //             $product = $products->firstWhere('id', $productId);
    //             return [
    //                 'product_id' => $productId,
    //                 'product_name' => $product->name ?? null,
    //                 'brand_name' => $product->brand->name ?? null,
    //                 'status' => $product->status ?? null,
    //                 'batches' => $stocks->map(function ($stock) {
    //                     return [
    //                         'batch_no' => $stock->batch_no,
    //                         'buy_price' => $stock->buy_price,
    //                         'cartons' => $stock->cartons,
    //                         'total_items' => $stock->total_items,
    //                         'missing_items' => $stock->missing_items,
    //                         'sold_items' => $stock->sold_items,
    //                         'available_items' => $stock->available_items,
    //                         'batch_id' => $stock->batch_id,
    //                         'created_at' => $stock->created_at,
    //                     ];
    //                 })->values(),
    //             ];
    //         });

    //         return response()->json(['data' => $groupedData]);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function getHistory(Request $request)
    // {
    //     try {
    //         $organization = Organization::where('id', '=', $request->company)->first();
    //         $selectedDate = $request->selectedDate;
    //         $productId = $request->productId;
    //         $brandId = $request->brandId;
    //         $brand = Brand::where('id',$brandId)->first();
    //         // Get all products with their brands
    //         $products = Product::with('brand')->get();

    //         setDatabaseConnectionForOrganization($organization->name);
    //         $excludedBatchIds = DB::table('sell_counter')
    //         ->pluck('batch_id');
    //         // Build the query for batches and cartons
    //         $query = DB::table('batches')
                
    //         ->select(
    //             // 'batches.*',
    //             // 'batches.batch_number as batch_no',                       
    //             'batches.product_id',
    //             'batches.unit',                     
    //             // 'batches.id as batch_id',
    //             DB::raw('SUM(batches.quantity) as total_quantity'),
    //             DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
    //             DB::raw('SUM(batches.buy_price) as total_buy_price'),
    //             DB::raw('MAX(batches.created_at) as first_created_at'),
    //             DB::raw('MAX(batches.invoice_no) as invoice')
    //         )
    //         // ->whereNotIn('batches.id', $excludedBatchIds)
    //         ->groupBy('batches.product_id', 'batches.unit')
    //         ->orderBy('product_id', 'ASC');
           
    //         $stocksList = $query->get();
   

    //         // Filter by productId if provided
    //         if ($productId) {
    //             $stocksList = $stocksList->filter(fn($stock) => $stock->product_id == $productId);
    //         }

    //         // Filter by brandId if provided
    //         if ($brandId) {
    //             $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
    //                 $product = $products->firstWhere('id', $stock->product_id);
    //                 return $product && $product->brand_id == $brandId;
    //             });
    //         }

    //         // Group data by product_id
    //         $groupedData = $stocksList->groupBy('product_id')->map(function ($stocks, $productId) use ($products) {
    //             $product = $products->firstWhere('id', $productId);
    //             return [
    //                 'product_id' => $productId,
    //                 'product_name' => $product->name ?? null,
    //                 'total_buy_price' => $stocks[0]->total_buy_price,
    //                 'brand_name' => $product->brand->name ?? null,
    //                 'unit' => $product->unit,
    //                 'total_quantity' => $stocks[0]->total_quantity,
    //                 'total_no_of_unit' => $stocks[0]->total_no_of_unit,
    //                 'status' => $product->status ?? null,  
    //                 'created_at' => $stocks[0]->first_created_at ?? null,    
    //                 'invoice' => $stocks[0]->invoice ?? null,                     
    //             ];
    //         });

    //           dd($groupedData);

    //         return response()->json(['data' => $groupedData]);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

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
                    DB::raw('MAX(batches.expiry_date) as expiry_date')
                )
                ->whereDate('batches.created_at', $selectedDate)
                ->groupBy('batches.product_id', 'batches.unit', 'batches.no_of_units')
                ->orderBy('product_id', 'ASC');
    
            $stocksList = $query->get();
    // dd($stocksList);
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
                    ];
                });
            });
    
    // dd($groupedData);
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


    // public function getHistory(Request $request)
    // {
    //     try {
    //         // $organization = Organization::where('id','=',$request->company)->first();

    //         // config(['database.connections.pgsql.database' => $organization->name]);
    //         // DB::purge('pgsql');
    //         // DB::connection('pgsql')->getPdo();
    //         setDatabaseConnection();
    //         $histories = Purchase_History::all();
    //         // dd($histories);

    //         if ($histories->isEmpty()) {
    //             return response()->json(['success' => false, 'message' => 'No history found for the given company.'], 404);
    //         }


    //         $formattedHistories = $histories->map(function ($history) {
    //             $details = json_decode($history->details, true);
    //            // dd($history->batch_id);
    //             if (!$details || !is_array($details)) {
    //                 return null;
    //             }

    //             return collect($details)->map(function ($detail) use ($history)  {

    //                 $totalCartons = count($detail['cartons'] ?? []);
    //                 $totalItems = collect($detail['cartons'] ?? [])->sum('no_of_items_inside');
    //                 $missingItems = collect($detail['cartons'] ?? [])->sum('missing_items');

    //                 return (object) [
    //                     'sku' => $detail['product']['sku'] ?? null,
    //                     'batch_no' => $detail['batch_number'] ?? null,
    //                     'buy_price' => $detail['buy_price'] ?? null,
    //                     'cartons' => $totalCartons,
    //                     'total_items' => $totalItems,
    //                     'missing_items' => $missingItems,
    //                     'batch_id' => $history->id,
    //                 ];
    //             });
    //         })->flatten(1)->filter();

    //         // dd($formattedHistories);

    //         return response()->json(['success' => true, 'data' => $formattedHistories], 200);
    //     } catch (\Exception $e) {
    //         \Log::error('Error retrieving history: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while fetching history.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    // public function detailHistory(Request $request, $id, $companyName)
    // {
    //     // dd("hello");

    //     setDatabaseConnection();
    //     // config(['database.connections.pgsql.database' => $companyName]);
    //     // DB::purge('pgsql');
    //     // DB::connection('pgsql')->getPdo();

    //     $stocks = DB::table('batches')
    //         ->join('products', 'batches.product_id', '=', 'products.id')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         ->select(
    //             'products.*',
    //             'batches.*',
    //             'cartons.carton_number',
    //             'cartons.no_of_items_inside',
    //             'cartons.missing_items'
    //         )
    //         ->where('batches.id', $id)
    //         ->orderBy('batches.id', 'DESC')
    //         ->get();

    //     // Grouping the cartons under the same batch
    //     $groupedStocks = $stocks->groupBy('batch_id')->map(function ($group) {
    //         $first = $group->first();
    //         $first->cartons = $group->map(function ($item) {
    //             return [
    //                 'carton_number' => $item->carton_number,
    //                 'no_of_items_inside' => $item->no_of_items_inside,
    //                 'missing_items' => $item->missing_items,
    //             ];
    //         })->values();
    //         return $first;
    //     })->first(); // Extract the first item to make it an object

    //     return view('admin.purchaseHistoryDetails', ['batch' => $groupedStocks]);

    //     //     $purchaseHistory = DB::table('purchase_history')
    //     //     ->where('id', '=', $id)
    //     //     ->first(); 

    //     // if (!$purchaseHistory) {

    //     //     return redirect()->back()->with('error', 'Purchase history not found.');
    //     // }

    //     // $details = json_decode($purchaseHistory->details);

    //     // $batch = collect($details)->map(function ($item) {
    //     //     if (isset($item->batch_number)) {
    //     //         $batch = [
    //     //             'batch_number' => $item->batch_number,
    //     //             'product_id' => $item->product_id,
    //     //             'manufacturing_date' => $item->manufacturing_date,
    //     //             'expiry_date' => $item->expiry_date,
    //     //             'base_price' => $item->base_price,
    //     //             'exchange_rate' => $item->exchange_rate,
    //     //             'buy_price' => $item->buy_price,
    //     //             'notes' => $item->notes ?? null,
    //     //         ];

    //     //         $cartons = collect($item->cartons)->map(function ($carton) {
    //     //             return [
    //     //                 'carton_number' => $carton->carton_number,
    //     //                 'no_of_items_inside' => $carton->no_of_items_inside,
    //     //                 'missing_items' => $carton->missing_items,
    //     //             ];
    //     //         });

    //     //         $batch['cartons'] = $cartons;

    //     //         return $batch;
    //     //     }

    //     //     return null;
    //     // })->filter();
    //     // $batch = $batch->first();

    //     //     //  dd($batch);
    //     //     return view('admin.purchaseHistoryDetails', ['batch' => $batch]);
    // }

    // public function detailHistory(Request $request, $id, $companyName)
    // {
    //     try {

    //         // Fetch all products first
    //         $products = Product::all();  // Or use any other query to fetch products

    //         // Set the database connection
    //         setDatabaseConnection();

    //         // Now, query the database for batch and carton details
    //         $stocks = DB::table('batches')
    //             // ->join('products', 'batches.product_id', '=', 'products.id')
    //             ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //             ->select(
    //                 // 'products.*',
    //                 'batches.*',
    //                 'cartons.carton_number',
    //                 'cartons.no_of_items_inside',
    //                 'cartons.missing_items'
    //             )
    //             ->where('batches.id', $id)
    //             ->orderBy('batches.id', 'DESC')
    //             ->get();

    //         // Grouping the cartons under the same batch
    //         $groupedStocks = $stocks->groupBy('batch_id')->map(function ($group) use ($products) {
    //             $first = $group->first();

    //             // Map the products with the stocks (you can map additional details if needed)
    //             $product = $products->firstWhere('id', $first->product_id);

    //             // Add the product details to the batch
    //             $first->product_name = $product ? $product->name : null;
    //             // $first->brand_name = $product && $product->brand ? $product->brand->name : null;

    //             $first->cartons = $group->map(function ($item) {
    //                 return [
    //                     'carton_number' => $item->carton_number,
    //                     'no_of_items_inside' => $item->no_of_items_inside,
    //                     'missing_items' => $item->missing_items,
    //                 ];
    //             })->values();

    //             return $first;
    //         })->first(); // Extract the first item to make it an object
    //         // dd($groupedStocks);
    //         return view('admin.purchaseHistoryDetails', ['batch' => $groupedStocks]);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


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
        // dd($request->all());
        // try {
            
        //     $organization = Organization::where('id', '=', $request->company)->first();
        //     config(['database.connections.pgsql.database' => $organization->name]);
        //     DB::purge('pgsql');
        //     DB::connection('pgsql')->getPdo();

        //     $query = Sell::orderBy('id', 'desc');
        //     if ($request->has('productId') && !empty($request->productId)) {
        //         $query->where('sell.sku', $request->productId);
        //     }
        //     if ($request->has('selectedDate') && !empty($request->selectedDate)) {
        //         $selectedDate = $request->selectedDate;
        //         $query->whereDate('created_at', $selectedDate);
        //     }
        //     $sells = $query->get();

        //     return response()->json(['data' => $sells]);
        // } catch (\Throwable $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }

        // try {
        //     if(auth()->user()->role == 1){
        //         $organization = Organization::where('id', $request->company)->first();
        //     }else{
        //         $organization = Organization::where('name', $request->company)->first();         
        //     }
        //     $selectedDate = $request->selectedDate;
        //     $productId = $request->productId;
        //     $brandId = $request->brandId;
    
        //     $products = Product::with('brand')->get();
    
        //     config(['database.connections.pgsql.database' => $organization->name]);
        //     DB::purge('pgsql');
        //     DB::connection('pgsql')->getPdo();
    
        //     $sellCounterSubquery = DB::table('sell_counter')
        //         ->select('batch_id','order_id','status','deleted_at', DB::raw('SUM(provided_no_of_cartons) as total_provided'))
        //         ->whereDate('created_at', $selectedDate)
        //         ->where('sell_counter.deleted_at',null)
        //         ->groupBy('batch_id','order_id','deleted_at','status');
    
        //     $query = DB::table('batches')
        //         ->JoinSub($sellCounterSubquery, 'sc', function ($join) {
        //             $join->on('batches.id', '=', 'sc.batch_id');
        //         })
        //         ->select(
        //             'batches.product_id',
        //             'batches.unit',
        //             'sc.order_id',
        //             // 'sc.status',
        //             // DB::raw('SUM(batches.quantity - COALESCE(sc.total_provided, 0)) as total_quantity'),
        //             DB::raw('SUM(batches.quantity) as total_quantity'),
        //             DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
        //             DB::raw('SUM(batches.buy_price) as total_buy_price'),
        //             DB::raw('MAX(batches.created_at) as first_created_at'),
        //             DB::raw('MAX(batches.invoice_no) as invoice'),
        //             DB::raw('MAX(batches.expiry_date) as expiry_date'),
        //             // DB::raw('MAX(sc.status) as status'),
                    
        //         )
        //         // ->whereDate('batches.created_at', $selectedDate)
        //         ->groupBy('batches.product_id', 'batches.unit', 'sc.order_id')
        //         ->orderBy('product_id', 'ASC');
    
        //     $stocksList = $query->get();

        //     if ($productId) {
        //         $stocksList = $stocksList->filter(function ($stock) use ($productId) {
        //             return $stock->product_id == $productId;
        //         });
        //     }
    
        //     if ($brandId) {
        //         $stocksList = $stocksList->filter(function ($stock) use ($brandId, $products) {
        //             $product = $products->firstWhere('id', $stock->product_id);
        //             return $product && $product->brand_id == $brandId;
        //         });
        //     }
    
        //     $groupedData = $stocksList->groupBy('product_id','order_id')->map(function ($stocks, $productId) use ($products) {
        //         $product = $products->firstWhere('id', $productId);
        //         return [
        //             'product_id' => $productId,
        //             'product_name' => $product->name ?? null,
        //             'total_buy_price' => $stocks[0]->total_buy_price,
        //             'brand_name' => $product->brand->name ?? null,
        //             'unit' => $product->unit ?? null,
        //             'total_quantity' => $stocks[0]->total_quantity,
        //             'total_no_of_unit' => $stocks[0]->total_no_of_unit,
        //             'status' => $product->status ?? null,
        //             'expiry_date' => $stocks[0]->expiry_date ?? null,
        //             'created_at' => $stocks[0]->first_created_at ?? null,
        //             'invoice' => $stocks[0]->invoice ?? null,
        //             'order_id' => $stocks[0]->order_id ?? null, 
        //             // 'status' => $stocks[0]->status ?? null, 
        //         ];
        //     });
    
        //     // dd($groupedData);
        //     return response()->json(['data' => $groupedData]);
        // } catch (\Throwable $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }

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
