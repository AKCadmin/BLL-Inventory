<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Brand;
use App\Models\Carton;
use App\Models\Product;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Purchase_History;
use App\Models\Sell;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StockController extends Controller
{
    // public function __construct()
    // {
    //     $this->setupDatabaseConnection();
    // }

    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        $organizations = Organization::orderBy('id', 'desc')->get();
        return view('stock.index', compact('brands', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        try {
            if (auth()->user()->cannot('add-purchase')) {
                abort(403);
            }

            // Get the database name for the primary connection
            if ($request->has('organizationName')) {
                $databaseName = $request->organizationName;
            } else {
                $databaseName = Session::get('db_name');
            }

            if (!$databaseName) {
                return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
            }
            $productSku = $request->input('SKU');
            $product = Product::where('id', $productSku)->first();

            // Set the primary database connection
            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();

            // $invoice_number = $request->invoice;
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product with the given SKU not found.'], 404);
            }

            $batches = $request->input('batches');
            $decodedBatches = [];
            foreach ($batches as $batch) {
                $decodedBatches[] = json_decode($batch, true);
            }

            DB::beginTransaction();

            $historyDetails = []; // To store all action details

            foreach ($decodedBatches as $batch) {
                $existingBatch = Batch::where('batch_number', $batch['batchNo'])->first();
                // if ($existingBatch) {
                //     DB::rollBack();
                //     return response()->json([
                //         'success' => false,
                //         'message' => "Batch number '{$batch['batchNo']}' already exists."
                //     ], 409);
                // }

                $batchModel = new Batch();
                $batchModel->batch_number = $batch['batchNo'];
                $batchModel->product_id = $product->id;
                $batchModel->brand_id = $request->brand_id;
                $batchModel->unit = $request->unit;
                $batchModel->internal_purchase = $request->internal_purchase == 1 ? true : false;
                $batchModel->manufacturing_date = $batch['manufacturingDate'] ?: null;
                $batchModel->expiry_date = $batch['expiryDate'] ?: null;
                $batchModel->base_price = $batch['basePrice'];
                $batchModel->exchange_rate = $batch['exchangeRate'];
                $batchModel->buy_price = $batch['buyPrice'];
                $batchModel->no_of_units = $batch['noOfUnits'];
                $batchModel->quantity = $batch['qty'];
                $batchModel->invoice_no = $request->invoice;
                $batchModel->customer = $product->customer;
                $batchModel->notes = $request->notes;
                $batchModel->save();

                $brand = Brand::findOrFail($request->brand_id);
                $brand->amount_credit += $batch['buyPrice'];
                $brand->save();

                $batchDetails = [
                    'batch_number' => $batchModel->batch_number,
                    'product_id' => $batchModel->product_id,
                    'manufacturing_date' => $batchModel->manufacturing_date,
                    'expiry_date' => $batchModel->expiry_date,
                    'base_price' => $batchModel->base_price,
                    'exchange_rate' => $batchModel->exchange_rate,
                    'buy_price' => $batchModel->buy_price,
                    'notes' => $batchModel->notes,
                    'cartons' => [],
                ];

                foreach ($batch['cartons'] as $index => $carton) {
                    if ($carton['missingItems'] > $carton['itemsInside']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Missing items cannot be more than items inside for carton '{$batch['batchNo']}-" . ($index + 1) . "'."
                        ], 422);
                    }

                    $cartonModel = new Carton();
                    $cartonModel->batch_id = $batchModel->id;
                    $cartonModel->carton_number = $batch['batchNo'] . '-' . ($index + 1);
                    $cartonModel->no_of_items_inside = $carton['itemsInside'];
                    $cartonModel->missing_items = $carton['missingItems'] ?: 0;
                    $cartonModel->save();

                    // Add carton details to batch history
                    $batchDetails['cartons'][] = [
                        'carton_number' => $cartonModel->carton_number,
                        'no_of_items_inside' => $cartonModel->no_of_items_inside,
                        'missing_items' => $cartonModel->missing_items,
                    ];
                }

                $historyDetails[] = $batchDetails; // Add batch and carton details to history
            }

            DB::commit();

            // Save the complete history
            Purchase_History::create([
                'action' => 'Batch and Carton Creation',
                'details' => json_encode($historyDetails),
                'user_id' => auth()->id(),
                'batch_id' => $batchModel->id, // Adding batch ID for association
            ]);

            return response()->json(['success' => true, 'message' => 'Batches and cartons stored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while creating the stock.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {

        try {
            if (auth()->user()->role == 1) {
                $companies = Company::all();
                $brands = Brand::all();
                setDatabaseConnection();
                $stocks = DB::table('batches')
                    //->join('products', 'batches.product_id', '=', 'products.id')
                    ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
                    ->select(
                        // 'products.sku',
                        'batches.batch_number as batch_no',
                        'batches.buy_price',
                        DB::raw('COUNT(cartons.id) as cartons'),
                        DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
                        DB::raw('SUM(cartons.missing_items) as missing_items'),
                        'batches.id as batch_id'
                    )
                    ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price')
                    ->orderBy('batches.id', 'DESC')
                    ->get();

                return view('admin.stockList', compact('stocks', 'companies', 'brands'));
            } else {
                $products = Product::with('brand')->get();

                setDatabaseConnection();
                // $excludedBatchIds = DB::table('sell_counter')
                //     ->pluck('batch_id');
                // Build the query for batches and cartons
                $query = DB::table('batches')

                    ->select(
                        // 'batches.*',
                        // 'batches.batch_number as batch_no',                       
                        'batches.product_id',
                        'batches.unit',
                        // 'batches.id as batch_id',
                        DB::raw('SUM(batches.quantity) as total_quantity'),
                        DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
                        DB::raw('SUM(batches.buy_price) as total_buy_price'),
                        DB::raw('MAX(batches.created_at) as first_created_at'),
                        DB::raw('MAX(batches.invoice_no) as invoice_no')
                    )
                    // ->whereNotIn('batches.id', $excludedBatchIds)
                    ->groupBy('batches.product_id', 'batches.unit')
                    ->orderBy('product_id', 'ASC');

                $stocksList = $query->get();

                $groupedData = $stocksList->groupBy('product_id')->map(function ($stocks, $productId) use ($products) {
                    $product = $products->firstWhere('id', $productId);
                    return [
                        'product_id' => $productId,
                        'product_name' => $product->name ?? null,
                        'total_buy_price' => $stocks[0]->total_buy_price,
                        'brand_name' => $product->brand->name ?? null,
                        'unit' => $product->unit,
                        'total_quantity' => $stocks[0]->total_quantity,
                        'total_no_of_unit' => $stocks[0]->total_no_of_unit,
                        'status' => $product->status ?? null,
                        'created_at' => $stocks[0]->first_created_at ?? null,
                        'invoice_no' => $stocks[0]->invoice_no ?? null,
                    ];
                });
                //  dd($groupedData);
                return view('admin.list', compact('groupedData'));
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function stockList(Request $request)
    {

        try {
            if (auth()->user()->role == 1) {
                $companies = Company::all();
                $brands = Brand::all();
                setDatabaseConnection();
                $stocks = DB::table('batches')
                    //->join('products', 'batches.product_id', '=', 'products.id')
                    ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
                    ->select(
                        // 'products.sku',
                        'batches.batch_number as batch_no',
                        'batches.buy_price',
                        DB::raw('COUNT(cartons.id) as cartons'),
                        DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
                        DB::raw('SUM(cartons.missing_items) as missing_items'),
                        'batches.id as batch_id'
                    )
                    ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price')
                    ->orderBy('batches.id', 'DESC')
                    ->get();
                return view('admin.stockList', compact('stocks', 'companies', 'brands'));
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function listByCompany(Request $request)
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
                ->select('batch_id', 'provided_no_of_cartons', DB::raw('SUM(provided_no_of_cartons) as total_provided'))
                ->whereDate('created_at', $selectedDate)
                ->groupBy('batch_id', 'provided_no_of_cartons');

            $query = DB::table('batches')
                ->leftJoinSub($sellCounterSubquery, 'sc', function ($join) {
                    $join->on('batches.id', '=', 'sc.batch_id');
                })
                ->select(
                    'batches.product_id',
                    'batches.unit',
                    'batches.no_of_units',
                    DB::raw('SUM(sc.provided_no_of_cartons) as provided_no_cartons'),
                    DB::raw('SUM(batches.quantity) as total_quantity'),
                    DB::raw('MAX(batches.no_of_units) as total_no_of_unit'),
                    // DB::raw('SUM(batches.buy_price) as total_buy_price'),
                    DB::raw('MAX(batches.created_at) as first_created_at'),
                    DB::raw('MAX(batches.invoice_no) as invoice'),
                    DB::raw('MAX(batches.expiry_date) as expiry_date')
                )
                ->when($selectedDate, function ($q) use ($selectedDate) {
                    return $q->whereDate('batches.created_at', $selectedDate);
                })
                ->groupBy('batches.product_id', 'batches.unit', 'batches.no_of_units')
                // ->orderBy('product_id', 'ASC');
                ->orderBy('first_created_at', 'DESC');

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
                        // 'total_buy_price' => $stock->total_buy_price ?? null,
                        'brand_name' => $product->brand->name ?? null,
                        'unit' => $product->unit,
                        'no_of_units' => $stock->no_of_units,
                        'total_quantity' => $stock->total_quantity,
                        'total_no_of_unit' => $stock->total_no_of_unit,
                        'status' => $product->status ?? null,
                        'expiry_date' => $stock->expiry_date ?? null,
                        'created_at' => $stock->first_created_at ?? null,
                        'invoice' => $stock->invoice ?? null,
                        'provided_no_catons' => $stock->provided_no_cartons,
                    ];
                });
            })->sortKeys();

            return response()->json($groupedData);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function listByProduct(Request $request)
    {
        try {

            config(['database.connections.pgsql.database' => $request->input('company')]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();


            $companyId = Company::select('id')->where('name', '=', $request->input('company'))->first();
            $products = DB::table('products')
                ->where('products.company_id', '=', $companyId)
                ->get();

            return response()->json($products);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {

        $product = Product::where('id', $id)->first();
        $brand = Brand::where('id', $product->brand_id)->first();
        // $organization = Organization::select('id','name')->where('name',3)->first();

        setDatabaseConnection();

        $batchData = DB::table('batches')
            ->leftjoin('sell', 'sell.batch_no', '=', 'batches.batch_number')
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
            return view('admin.editStock', compact('groupedData', 'brand'));
        }
        // dd($groupedData);
        return view('admin.edit', compact('groupedData', 'brand'));
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request)
    // {

    //     try {

    //         setDatabaseConnection();

    //         $batches = $request->input('batches');
    //         // dd($batches);
    //         if (!$batches || !is_array($batches)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid batches data.',
    //             ], 400);
    //         }

    //         DB::beginTransaction();

    //         $historyDetails = [];
    //         // $invoice_number = $request->invoice;
    //         //    dd($request->all());
    //         foreach ($batches as $batch) {
    //             $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
    //             $isNewBatch = !$batchModel->exists;
    //             $batchModel->brand_id = $batch['brand_id'] ?: null;
    //             $batchModel->product_id = $batch['product_id'] ?: null;
    //             $batchModel->unit = $batch['unit'] ?: null;
    //             $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
    //             $batchModel->expiry_date = $batch['expiry_date'] ?: null;
    //             $batchModel->base_price = $batch['base_price'];
    //             $batchModel->exchange_rate = $batch['exchange_rate'];
    //             $batchModel->buy_price = $batch['buy_price'];
    //             $batchModel->no_of_units = $batch['no_of_units'];
    //             $batchModel->quantity = $batch['quantity'];
    //             $batchModel->invoice_no = $batch['invoice'];
    //             $batchModel->notes = $batch['notes'];
    //             $batchModel->save();

    //             $cartonIds = [];
    //             $batchDetails = [
    //                 'batch_number' => $batchModel->batch_number,
    //                 'product_id' => $batchModel->product_id,
    //                 'unit' => $batchModel->unit,
    //                 'manufacturing_date' => $batchModel->manufacturing_date,
    //                 'expiry_date' => $batchModel->expiry_date,
    //                 'base_price' => $batchModel->base_price,
    //                 'exchange_rate' => $batchModel->exchange_rate,
    //                 'buy_price' => $batchModel->buy_price,
    //                 'no_of_units' => $batchModel->no_of_units,
    //                 'quantity' => $batchModel->quantity,
    //                 'is_new' => $isNewBatch,
    //                 'cartons' => [],
    //             ];

    //             $historyDetails[] = $batchDetails; // Add batch and cartons to history
    //         }
    //         // dd($batches);

    //         if (!empty($batch['hospitalPrice']) || !empty($batch['wholesalePrice']) || !empty($batch['retailPrice'])) {
    //             foreach ($batches as $batch) {
    //                 $batchModel = Sell::firstOrNew(['batch_id' => $batch['batch_id']]);
    //                 $batchModel->sku  = $batch['product_id'];
    //                 $batchModel->batch_no  = $batch['batch_no'];
    //                 $batchModel->hospital_price  = $batch['hospitalPrice'];
    //                 $batchModel->wholesale_price  = $batch['wholesalePrice'];
    //                 $batchModel->retail_price  = $batch['retailPrice'];
    //                 $batchModel->valid_from  = $batch['manufacturing_date'];
    //                 $batchModel->valid_to  = $batch['expiry_date'];
    //                 $batchModel->batch_id  = $batch['batch_id'];
    //                 $batchModel->save();
    //             }
    //         }

    //         DB::commit();

    //         // Record update history
    //         Purchase_History::create([
    //             'action' => 'Batch and Carton Update',
    //             'details' => json_encode($historyDetails),
    //             'user_id' => auth()->id(),
    //             'batch_id' => $batchModel->id,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Batches and cartons updated successfully, and history recorded.',
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Error updating batches: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while updating batches and cartons.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function update(Request $request)
    {
        try {
            setDatabaseConnection();

            $batches = $request->input('batches');
            if (!$batches || !is_array($batches)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid batches data.',
                ], 400);
            }

            $invoice = $batches[0]['invoice'] ?? null; // Get invoice number from first batch

            DB::beginTransaction();

            $historyDetails = [];

            foreach ($batches as $batch) {
                // Ensure all batches belong to the same invoice
                if ($invoice != $batch['invoice']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'All batches must belong to the same invoice.',
                    ], 400);
                }

                $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
                $isNewBatch = !$batchModel->exists;
                $batchModel->brand_id = $batch['brand_id'] ?? null;
                $batchModel->product_id = $batch['product_id'] ?? null;
                $batchModel->unit = $batch['unit'] ?? null;
                $batchModel->manufacturing_date = $batch['manufacturing_date'] ?? null;
                $batchModel->expiry_date = $batch['expiry_date'] ?? null;
                $batchModel->base_price = $batch['base_price'];
                $batchModel->exchange_rate = $batch['exchange_rate'];
                $batchModel->buy_price = $batch['buy_price'];
                $batchModel->no_of_units = $batch['no_of_units'];
                $batchModel->quantity = $batch['quantity'];
                $batchModel->invoice_no = $batch['invoice'];
                $batchModel->notes = $batch['notes'] ?? null;
                $batchModel->save();

                // Move the pricing information update inside the loop
                if (!empty($batch['hospitalPrice']) || !empty($batch['wholesalePrice']) || !empty($batch['retailPrice'])) {
                    $sellModel = Sell::firstOrNew(['batch_id' => $batchModel->id]);
                    $sellModel->sku = $batch['product_id'];
                    $sellModel->batch_no = $batch['batch_no'];
                    $sellModel->hospital_price = $batch['hospitalPrice'] ?? null;
                    $sellModel->wholesale_price = $batch['wholesalePrice'] ?? null;
                    $sellModel->retail_price = $batch['retailPrice'] ?? null;
                    $sellModel->valid_from = $batch['manufacturing_date'] ?? null;
                    $sellModel->valid_to = $batch['expiry_date'] ?? null;
                    $sellModel->batch_id = $batchModel->id; // Use the model's ID, not batch_id from request
                    $sellModel->save();
                }

                $batchDetails = [
                    'batch_number' => $batchModel->batch_number,
                    'product_id' => $batchModel->product_id,
                    'unit' => $batchModel->unit,
                    'manufacturing_date' => $batchModel->manufacturing_date,
                    'expiry_date' => $batchModel->expiry_date,
                    'base_price' => $batchModel->base_price,
                    'exchange_rate' => $batchModel->exchange_rate,
                    'buy_price' => $batchModel->buy_price,
                    'no_of_units' => $batchModel->no_of_units,
                    'quantity' => $batchModel->quantity,
                    'is_new' => $isNewBatch,
                ];

                $historyDetails[] = $batchDetails;
            }

            // Record update history for the entire invoice
            Purchase_History::create([
                'action' => 'Invoice Batches Update',
                'details' => json_encode($historyDetails),
                'user_id' => auth()->id(),
                'invoice_no' => $invoice,
                'batch_id' => $batchModel->id, // Don't associate with a specific batch since this is for multiple batches
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice batches updated successfully, and history recorded.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating invoice batches: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating invoice batches.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Check if the user has the required permission
            // if (auth()->user()->cannot('delete-purchase')) {
            //     abort(403);
            // }

            // Get the product by its ID
            $product = Product::find($id);

            $databaseName = Session::get('db_name');

            if (!$databaseName) {
                return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
            }

            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
            }

            // Begin a database transaction
            DB::beginTransaction();

            // Delete related batches and cartons
            $batches = DB::table('batches')->where('product_id', $id)->get();
            foreach ($batches as $batch) {
                $batchInSellCounter = DB::table('sell_counter')->where('batch_id', $batch->id)->exists();
                $batchInSellCarton = DB::table('cartons')->join('sell_carton', 'cartons.id', '=', 'sell_carton.carton_id')->where('cartons.batch_id', $batch->id)->exists();
                if ($batchInSellCounter) {
                    continue;
                }
                if ($batchInSellCarton) {
                    continue;
                }

                DB::table('cartons')->where('batch_id', $batch->id)->delete();

                // Delete the batch itself
                DB::table('batches')->where('id', $batch->id)->delete();
            }

            // Delete the product
            //$product->delete();

            // Commit the transaction
            DB::commit();

            // Log the action
            // Purchase_History::create([
            //     'action' => 'Product Deletion',
            //     'details' => json_encode(['product_id' => $product->id]),
            //     'user_id' => 1,
            // ]);

            return response()->json(['success' => true, 'message' => 'Product and its related batches and cartons deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while deleting the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function stockDetails(Request $request, $productId, $encodedCreatedAt, $totalNoOfUnits)
    // {
    //     $product = Product::find($productId);
    //     $brand = Brand::find($product->brand_id);
    //     $createdAt = base64_decode($encodedCreatedAt);
    //     // dd($totalNoOfUnits);
    //     $databaseName = Session::get('db_name');

    //     if (!$databaseName) {
    //         return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
    //     }

    //     config(['database.connections.pgsql.database' => $databaseName]);
    //     DB::purge('pgsql');
    //     DB::connection('pgsql')->getPdo();

    //     // dd($invoice);
    //     $data = DB::table('batches')
    //         ->leftJoin('sell_counter', 'batches.id', '=', 'sell_counter.batch_id')
    //         ->leftJoin('sell', 'batches.batch_number', '=', 'sell.batch_no')
    //         ->select(
    //             'batches.batch_number',
    //             'batches.product_id',
    //             'batches.brand_id',
    //             'batches.unit',
    //             'batches.base_price',
    //             'batches.buy_price',
    //             'batches.quantity as batch_quantity',
    //             'batches.no_of_units',
    //             'batches.invoice_no',
    //             'batches.created_at',
    //             'batches.expiry_date',
    //             'sell_counter.price',
    //             'sell_counter.customer_type',
    //             'sell.retail_price',
    //             'sell.wholesale_price',
    //             'sell.hospital_price',
    //             DB::raw('(batches.quantity + COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as purchase_quantity'),
    //             DB::raw('COALESCE(SUM(sell_counter.provided_no_of_cartons), 0) as sold_cartons'),
    //             DB::raw('(batches.quantity - COALESCE(SUM(sell_counter.provided_no_of_cartons), 0)) as remaining_quantity'),
    //         )
    //         // ->where(['batches.invoice_no'=> $invoice])
    //         ->where(['batches.product_id' => $productId])
    //         ->whereRaw('DATE(batches.created_at) = ?', [$createdAt])
    //         ->where(['batches.no_of_units' => $totalNoOfUnits])
    //         ->groupBy(
    //             'batches.id',
    //             'batches.batch_number',
    //             'batches.product_id',
    //             'batches.brand_id',
    //             'batches.unit',
    //             'batches.no_of_units',
    //             'batches.base_price',
    //             'batches.buy_price',
    //             'batches.quantity',
    //             'batches.invoice_no',
    //             'batches.created_at',
    //             'batches.expiry_date',
    //             'sell_counter.price',
    //             'sell_counter.customer_type',
    //             'sell.retail_price',
    //             'sell.wholesale_price',
    //             'sell.hospital_price',
    //         )
    //         ->get();

    //     // dd($data);

    //     return view('stock.details', compact('data', 'product', 'brand', 'createdAt'));
    // }

    public function stockDetails(Request $request, $productId, $encodedCreatedAt, $totalNoOfUnits, $invoice)
    {
        $product = Product::find($productId);
        $brand = Brand::find($product->brand_id);
        $createdAt = base64_decode($encodedCreatedAt);
        $sessionData = session()->all();

        dd($sessionData); // or print_r($sessionData);
        // Configure database connection
        $databaseName = Session::get('db_name');
        if (!$databaseName) {
            return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
        }
        config(['database.connections.pgsql.database' => $databaseName]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        // Get batch data
        $batches = DB::table('batches')
            ->where('product_id', $productId)
            ->whereRaw('DATE(created_at) = ?', [$createdAt])
            ->where('no_of_units', $totalNoOfUnits)
            ->where('invoice_no', $invoice)
            ->select(
                'id',
                'batch_number',
                'product_id',
                'brand_id',
                'unit',
                'base_price',
                'buy_price',
                'quantity',
                'no_of_units',
                'invoice_no',
                'created_at',
                'expiry_date'
            )
            ->get();

        // Process each batch to get its sales information
        $batchWiseData = [];
        foreach ($batches as $batch) {
            // Get sales counter data for this batch
            $salesData = DB::table('sell_counter')
                ->where('batch_id', $batch->id)
                ->select(
                    'price',
                    'customer_type',
                    'provided_no_of_cartons'
                )
                ->get();

            // Get pricing information
            $pricingData = DB::table('sell')
                ->where('batch_no', $batch->batch_number)
                ->select('retail_price', 'wholesale_price', 'hospital_price')
                ->first();

            // Calculate quantities
            $soldCartons = $salesData->sum('provided_no_of_cartons') ?? 0;
            $purchaseQuantity = $batch->quantity + $soldCartons;
            $remainingQuantity = $batch->quantity - $soldCartons;

            // Prepare batch data with sales information
            $batchWiseData[] = [
                'batch_number' => $batch->batch_number,
                'product_id' => $batch->product_id,
                'brand_id' => $batch->brand_id,
                'unit' => $batch->unit,
                'base_price' => $batch->base_price,
                'buy_price' => $batch->buy_price,
                'batch_quantity' => $batch->quantity,
                'no_of_units' => $batch->no_of_units,
                'invoice_no' => $batch->invoice_no,
                'created_at' => $batch->created_at,
                'expiry_date' => $batch->expiry_date,
                'price' => $salesData->isNotEmpty() ? $salesData->first()->price : null,
                'customer_type' => $salesData->isNotEmpty() ? $salesData->first()->customer_type : null,
                'retail_price' => $pricingData ? $pricingData->retail_price : null,
                'wholesale_price' => $pricingData ? $pricingData->wholesale_price : null,
                'hospital_price' => $pricingData ? $pricingData->hospital_price : null,
                'purchase_quantity' => $purchaseQuantity,
                'sold_cartons' => $soldCartons,
                'remaining_quantity' => $remainingQuantity,
                'sales_details' => $salesData // Additional detailed sales information per batch
            ];
        }
        //  dd($batchWiseData);
        return view('stock.details', compact('batchWiseData', 'product', 'brand', 'createdAt'));
    }
}
