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
        return view('stock.index', compact('brands'));
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
    // public function store(Request $request)
    // {

    //     $batches = $request->input('batches'); // Retrieve all batches
    //     $decodedBatches = [];

    //     foreach ($batches as $batch) {
    //         $decodedBatches[] = json_decode($batch, true); // Decode each JSON string
    //     }

    //     dd($decodedBatches);
    // }

    // public function store(Request $request)
    // {
    //     try {

    //         if (auth()->user()->cannot('add-purchase')) {
    //             abort(403); 
    //         }
    //         $databaseName = Session::get('db_name');
    //         // dd($databaseName);
    //         config(['database.connections.pgsql.database' => $databaseName]);
    //         DB::purge('pgsql');
    //         DB::connection('pgsql')->getPdo();



    //         if (!$databaseName) {
    //             return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
    //         }
    //         // if($request->db_name){
    //            // $dbName = $databseName;
    //             // config(['database.connections.pgsql.database' => $dbName]);
    //             // DB::purge('pgsql');
    //             // DB::connection('pgsql')->getPdo();
    //         // }

    //         // $batches = json_decode($request->input('batches'), true);

    //         // $rules = [
    //         //     'SKU' => 'required|string|max:255',
    //         //     'batches' => 'required|array',
    //         //     'batches.*.batchNo' => 'required|string|max:255',
    //         //     'batches.*.manufacturingDate' => 'nullable|date',
    //         //     'batches.*.expiryDate' => 'nullable|date',
    //         //     'batches.*.basePrice' => 'required|numeric|min:0',
    //         //     'batches.*.exchangeRate' => 'required|numeric|min:0',
    //         //     'batches.*.buyPrice' => 'required|numeric|min:0',
    //         //     'batches.*.notes' => 'nullable|string',
    //         //     'batches.*.cartons' => 'required|array',
    //         //     'batches.*.cartons.*.itemsInside' => 'required|integer|min:0',
    //         //     'batches.*.cartons.*.missingItems' => 'nullable|integer|min:0',
    //         // ];

    //         // $validator = Validator::make($request->all(), $rules);

    //         // if ($validator->fails()) {
    //         //     return response()->json([
    //         //         'success' => false,
    //         //         'message' => 'Validation errors.',
    //         //         'errors' => $validator->errors(),
    //         //     ], 422);
    //         // }
    //         $productSku = $request->input('SKU');
    //         $product = Product::where('sku', $productSku)->first();
    //         if (!$product) {
    //             return response()->json(['success' => false, 'message' => 'Product with the given SKU not found.'], 404);
    //         }

    //         $batches = $request->input('batches');
    //         $decodedBatches = [];

    //         foreach ($batches as $batch) {
    //             $decodedBatches[] = json_decode($batch, true);
    //         }

    //         DB::beginTransaction();

    //         foreach ($decodedBatches as $batch) {
    //             $existingBatch = Batch::where('batch_number', $batch['batchNo'])->first();
    //             if ($existingBatch) {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => "Batch number '{$batch['batchNo']}' already exists."
    //                 ], 409);
    //             }

    //             // Create batch
    //             $batchModel = new Batch();
    //             $batchModel->batch_number = $batch['batchNo'];
    //             $batchModel->product_id = $product->id;
    //             $batchModel->manufacturing_date = $batch['manufacturingDate'] ?: null;
    //             $batchModel->expiry_date = $batch['expiryDate'] ?: null;
    //             $batchModel->base_price = $batch['basePrice'];
    //             $batchModel->exchange_rate = $batch['exchangeRate'];
    //             $batchModel->buy_price = $batch['buyPrice'];
    //             $batchModel->notes = $batch['notes'];
    //             $batchModel->save();

    //             // Create cartons
    //             foreach ($batch['cartons'] as $index => $carton) {
    //                 if ($carton['missingItems'] > $carton['itemsInside']) {
    //                     DB::rollBack();
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Missing items cannot be more than items inside for carton '{$batch['batchNo']}-" . ($index + 1) . "'."
    //                     ], 422);
    //                 }
    //                 $cartonModel = new Carton();
    //                 $cartonModel->batch_id = $batchModel->id;
    //                 $cartonModel->carton_number = $batch['batchNo'] . '-' . ($index + 1);
    //                 $cartonModel->no_of_items_inside = $carton['itemsInside'];
    //                 $cartonModel->missing_items = $carton['missingItems'] ?: 0;
    //                 $cartonModel->save();
    //             }
    //         }

    //         DB::commit();

    //         return response()->json(['success' => true, 'message' => 'Batches and cartons stored successfully.']);
    //     } catch (\Exception $e) {
    //         // Rollback transaction on error
    //         DB::rollBack();
    //         \Log::error('Error creating stock: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred while creating the stock.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         if (auth()->user()->cannot('add-purchase')) {
    //             abort(403);
    //         }

    //         // Get the database name for the primary connection
    //         $databaseName = Session::get('db_name');
    //         // dd($databaseName);

    //         if (!$databaseName) {
    //             return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
    //         }

    //         // Set the primary database connection
    //         config(['database.connections.pgsql.database' => $databaseName]);
    //         DB::purge('pgsql');
    //         DB::connection('pgsql')->getPdo();

    //         $productSku = $request->input('SKU');
    //         $product = Product::where('name', $productSku)->first();
    //         // dd($product);
    //         if (!$product) {
    //             return response()->json(['success' => false, 'message' => 'Product with the given SKU not found.'], 404);
    //         }

    //         $batches = $request->input('batches');
    //         $decodedBatches = [];

    //         foreach ($batches as $batch) {
    //             $decodedBatches[] = json_decode($batch, true);
    //         }

    //         // Start transaction for the primary database
    //         DB::beginTransaction();

    //         foreach ($decodedBatches as $batch) {
    //             // Check if batch already exists in the primary database
    //             $existingBatch = Batch::where('batch_number', $batch['batchNo'])->first();
    //             if ($existingBatch) {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => "Batch number '{$batch['batchNo']}' already exists."
    //                 ], 409);
    //             }

    //             // Create batch in the primary database
    //             $batchModel = new Batch();
    //             $batchModel->batch_number = $batch['batchNo'];
    //             $batchModel->product_id = $product->id;
    //             $batchModel->manufacturing_date = $batch['manufacturingDate'] ?: null;
    //             $batchModel->expiry_date = $batch['expiryDate'] ?: null;
    //             $batchModel->base_price = $batch['basePrice'];
    //             $batchModel->exchange_rate = $batch['exchangeRate'];
    //             $batchModel->buy_price = $batch['buyPrice'];
    //             $batchModel->notes = $batch['notes'];
    //             $batchModel->save();

    //             // Create cartons in the primary database
    //             foreach ($batch['cartons'] as $index => $carton) {
    //                 if ($carton['missingItems'] > $carton['itemsInside']) {
    //                     DB::rollBack();
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Missing items cannot be more than items inside for carton '{$batch['batchNo']}-" . ($index + 1) . "'."
    //                     ], 422);
    //                 }
    //                 $cartonModel = new Carton();
    //                 $cartonModel->batch_id = $batchModel->id;
    //                 $cartonModel->carton_number = $batch['batchNo'] . '-' . ($index + 1);
    //                 $cartonModel->no_of_items_inside = $carton['itemsInside'];
    //                 $cartonModel->missing_items = $carton['missingItems'] ?: 0;
    //                 $cartonModel->save();
    //             }
    //         }

    //         // Commit the transaction for the primary database
    //         DB::commit();

    //         return response()->json(['success' => true, 'message' => 'Batches and cartons stored successfully in both databases.']);
    //     } catch (\Exception $e) {
    //         // Rollback transactions on error
    //         DB::rollBack();
    //         \Log::error('Error creating stock: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred while creating the stock.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {

          
        try {
            if (auth()->user()->cannot('add-purchase')) {
                abort(403);
            }

            // Get the database name for the primary connection
            $databaseName = Session::get('db_name');

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
                if ($existingBatch) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Batch number '{$batch['batchNo']}' already exists."
                    ], 409);
                }

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
                // $batchModel->notes = $batch['notes'];
                $batchModel->save();

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



    // public function list(Request $request)
    // {

    //     $companies = Company::all();
    //     $brands = Brand::all();
    //     setDatabaseConnection();
    //     $stocks = DB::table('batches')
    //         //->join('products', 'batches.product_id', '=', 'products.id')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         ->select(
    //             // 'products.sku',
    //             'batches.batch_number as batch_no',
    //             'batches.buy_price',
    //             DB::raw('COUNT(cartons.id) as cartons'),
    //             DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
    //             DB::raw('SUM(cartons.missing_items) as missing_items'),
    //             'batches.id as batch_id'
    //         )
    //         ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price')
    //         ->orderBy('batches.id', 'DESC')
    //         ->get();

    //     if (auth()->user()->role == 1) {
    //         return view('admin.stockList', compact('stocks', 'companies', 'brands'));
    //     } else {
    //         return view('admin.list', compact('stocks'));
    //     }
    // }

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

                // dd($stocksList);
                // Group data by product_id
                // $groupedData = $stocksList->groupBy('product_id')->map(function ($stocks, $productId) use ($products) {
                //     $product = $products->firstWhere('id', $productId);
                //     return [
                //         'product_id' => $productId,
                //         'product_name' => $product->name ?? null,
                //         'brand_name' => $product->brand->name ?? null,
                //         'status' => $product->status ?? null,
                //         'batches' => $stocks->map(function ($stock) {
                //             return [
                //                 'batch_no' => $stock->batch_no,
                //                 'buy_price' => $stock->buy_price,
                //                 'cartons' => $stock->cartons,
                //                 'total_items' => $stock->total_items,
                //                 'missing_items' => $stock->missing_items,
                //                 'sold_items' => $stock->sold_items,
                //                 'available_items' => $stock->available_items,
                //                 'batch_id' => $stock->batch_id,
                //                 'created_at' => $stock->created_at
                //             ];
                //         })->values(),
                //     ];
                // });

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

    // public function listByCompany(Request $request)
    // {
    //     $organization = Organization::where('id', '=', $request->company)->first();
    //     $selectedDate = $request->selectedDate;
    //     $isToday = $selectedDate === now()->toDateString();

    //     // dd($isToday);
    //     // dd($selectedDate);
    //     // Dynamically set the database connection
    //     // dd($organization);
    //     // config(['database.connections.pgsql.database' => $organization->name]);
    //     // DB::purge('pgsql');
    //     // DB::connection('pgsql')->getPdo();

    //     // Build the query
    //     // $query = DB::table('batches')
    //     //     ->join('products', 'batches.product_id', '=', 'products.id')
    //     //     ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //     //     ->select(

    //     //         'products.name',
    //     //         'batches.batch_number as batch_no',
    //     //         'batches.buy_price',
    //     //         DB::raw('COUNT(cartons.id) as cartons'),
    //     //         DB::raw('COUNT(batches.id) as totalBatches'),
    //     //         DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
    //     //         DB::raw('SUM(cartons.missing_items) as missing_items'),
    //     //         'batches.id as batch_id',
    //     //         'products.status',
    //     //     )
    //     //     ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'products.name','products.status')
    //     //     ->orderBy('batches.id', 'DESC');

    //     // // Apply product filter if provided
    //     // if ($request->input('productId')) {
    //     //     $query->where('products.id', '=', $request->input('productId'));
    //     // }

    //     // // Execute the query and get the results
    //     // $stocks = $query->get();


    //     $products = Product::with('brand')->get();

    //     config(['database.connections.pgsql.database' => $organization->name]);
    //     DB::purge('pgsql');
    //     DB::connection('pgsql')->getPdo(); // Switch to the organization's database


    //     $query = DB::table('batches')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         // Use the 'sell_carton' table directly from the current organization database
    //         ->leftJoinSub(
    //             DB::table('sell_carton')
    //                 ->select(
    //                     'sell_carton.carton_id',
    //                     DB::raw('SUM(sell_carton.no_of_items_sell) as total_sold_items')
    //                 )
    //                 ->whereDate('sell_carton.created_at', '>', $selectedDate)
    //                 ->groupBy('sell_carton.carton_id'),
    //             'sell_carton',
    //             'cartons.id',
    //             '=',
    //             'sell_carton.carton_id'
    //         )

    //         // ->join('products', 'batches.product_id', '=', 'products.id')
    //         //  Join 'products' using the current organization database connection
    //         // ->join(
    //         //     DB::connection('pgsqlmain')->table('products'), // Join using the correct connection
    //         //     'batches.product_id',
    //         //     '=',
    //         //     'products.id'
    //         // )
    //         ->select(
    //             // 'products.name',
    //             'batches.batch_number as batch_no',
    //             'batches.buy_price',
    //             'batches.product_id',
    //             DB::raw('COUNT(cartons.id) as cartons'),
    //             DB::raw('COUNT(batches.id) as totalBatches'),
    //             DB::raw('COALESCE(SUM(cartons.no_of_items_inside), 0) as total_items'),
    //             DB::raw('SUM(cartons.missing_items) as missing_items'),
    //             DB::raw('COALESCE(SUM(sell_carton.total_sold_items), 0) as sold_items'),
    //             DB::raw('SUM(cartons.no_of_items_inside) + COALESCE(SUM(sell_carton.total_sold_items), 0) as available_items'),
    //             'batches.id as batch_id',
    //             // 'products.status'
    //         )
    //         ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'batches.product_id')
    //         ->orderBy('batches.id', 'DESC');

    //     $stocksList = $query->get();

    //     $matchingStocks = $stocksList->filter(function ($stock) use ($products) {
    //         // print_r($products);
    //         return $products->contains('id', $stock->product_id);
    //     });

    //     $stocks = $matchingStocks->map(function ($stocksList) use ($products) {
    //         $product = $products->firstWhere('id', $stocksList->product_id);
    //         // print_r($stocksList);
    //         return [
    //             'product_id' => $stocksList->product_id,
    //             'brand_name' => $product->brand ? $product->brand->name : null,
    //             'product_name' => $product ? $product->name : null, // Add product name
    //             'batch_no' => $stocksList->batch_no,
    //             'buy_price' => $stocksList->buy_price,
    //             'cartons' => $stocksList->cartons,
    //             'total_batches' => $stock->totalBatches ?? null,
    //             'total_items' => $stocksList->total_items,
    //             'missing_items' => $stocksList->missing_items,
    //             'sold_items' => $stocksList->sold_items,
    //             'available_items' => $stocksList->available_items,
    //             'batch_id' => $stocksList->batch_id,
    //             'status' => $product->status ?? null,
    //         ];
    //     });

    //     // dd($matchingStocksWithNames);


    //     // Apply product filter if provided
    //     // if ($request->input('productId')) {
    //     //     $query->where('products.id', '=', $request->input('productId'));
    //     // }


    //     // Apply date filter if selectedDate is provided
    //     // if ($selectedDate) {
    //     //     $query->whereDate('cartons.created_at', '<=', $selectedDate)
    //     //           ->where(function($subQuery) use ($selectedDate) {
    //     //               $subQuery->whereNull('sell_carton.created_at')
    //     //                        ->orWhereDate('sell_carton.created_at', '>', $selectedDate);
    //     //           });
    //     // }

    //     // Execute the query and get the results


    //     //         $sql = $query->toSql();
    //     // $bindings = $query->getBindings();
    //     // dd($sql, $bindings);
    //     // $stocks = $query->get();

    //     return response()->json($stocks);
    // }

    // public function listByCompany(Request $request)
    // {
       
    //     try {
    //         $organization = Organization::where('id', '=', $request->company)->first();
    //         $selectedDate = $request->selectedDate;
    //         $isToday = $selectedDate === now()->toDateString();
    //         $productId = $request->productId;
    //         $brandId = $request->brandId;

    //         // Get all products with their brands
    //         $products = Product::with('brand')->get();

    //         // Switch database to the organization's database
    //         config(['database.connections.pgsql.database' => $organization->name]);
    //         DB::purge('pgsql');
    //         DB::connection('pgsql')->getPdo();

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

    //         return response()->json($stocks);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

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
                    DB::raw('SUM(batches.quantity - COALESCE(sc.total_provided, 0)) as total_quantity'),
                    DB::raw('SUM(batches.no_of_units) as total_no_of_unit'),
                    DB::raw('SUM(batches.buy_price) as total_buy_price'),
                    DB::raw('MAX(batches.created_at) as first_created_at'),
                    DB::raw('MAX(batches.expiry_date) as expiry_date')
                )
                ->whereDate('batches.created_at', $selectedDate)
                ->groupBy('batches.product_id', 'batches.unit')
                ->orderBy('product_id', 'ASC');
    
            $stocksList = $query->get();
    // dd($stocksList);
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
                    'expiry_date' => $stocks[0]->expiry_date ?? null,
                    'created_at' => $stocks[0]->first_created_at ?? null,
                ];
            });
    
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
    // public function show(string $id)
    // {

    //     setDatabaseConnection();

    //     $batchData = DB::table('batches')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         ->where('batches.id', $id)
    //         ->select(
    //             'batches.id as batch_id',
    //             'batches.batch_number',
    //             'batches.product_id',
    //             'batches.manufacturing_date',
    //             'batches.expiry_date',
    //             'batches.base_price',
    //             'batches.exchange_rate',
    //             'batches.buy_price',
    //             'batches.notes',
    //             'batches.created_at',
    //             'batches.updated_at',
    //             'cartons.id as carton_id',
    //             'cartons.carton_number',
    //             'cartons.no_of_items_inside',
    //             'cartons.missing_items'
    //         )
    //         ->get();

    //     // Check if batch data is empty
    //     if ($batchData->isEmpty()) {
    //         return redirect()->route('stock.list')->with('error', 'Stock batch not found.');
    //     }

    //     // Group cartons under a single batch
    //     $groupedData = $batchData->groupBy('batch_id')->map(function ($items) {
    //         $batch = $items->first(); // Common batch details

    //         return [
    //             'batch_id' => $batch->batch_id,
    //             'batch_number' => $batch->batch_number,
    //             'product_id' => $batch->product_id,
    //             'manufacturing_date' => $batch->manufacturing_date,
    //             'expiry_date' => $batch->expiry_date,
    //             'base_price' => $batch->base_price,
    //             'exchange_rate' => $batch->exchange_rate,
    //             'buy_price' => $batch->buy_price,
    //             'notes' => $batch->notes,
    //             'created_at' => $batch->created_at,
    //             'updated_at' => $batch->updated_at,
    //             'cartons' => $items->map(function ($item) {
    //                 return [
    //                     'carton_id' => $item->carton_id,
    //                     'carton_number' => $item->carton_number,
    //                     'no_of_items_inside' => $item->no_of_items_inside,
    //                     'missing_items' => $item->missing_items,
    //                 ];
    //             })->values(),
    //         ];
    //     });



    //     return view('admin.edit', compact('groupedData'));
    // }

    // public function show(string $id)
    // {

    //     // $organization = Organization::select('id','name')->where('name',3)->first();
    //     // dd($organization);
    //     setDatabaseConnection();
    //     $excludedBatchIds = DB::table('sell_counter')
    //         ->pluck('batch_id');
    //     // Filter by product_id instead of batch ID
    //     $batchData = DB::table('batches')
    //         ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
    //         ->where('batches.product_id', $id)
    //         ->whereNotIn('batches.id', $excludedBatchIds)
    //         ->select(
    //             'batches.id as batch_id',
    //             'batches.batch_number',
    //             'batches.product_id',
    //             'batches.manufacturing_date',
    //             'batches.expiry_date',
    //             'batches.base_price',
    //             'batches.exchange_rate',
    //             'batches.buy_price',
    //             'batches.notes',
    //             'batches.created_at',
    //             'batches.updated_at',
    //             'cartons.id as carton_id',
    //             'cartons.carton_number',
    //             'cartons.no_of_items_inside',
    //             'cartons.missing_items'
    //         )
    //         ->get();

    //     // Check if batch data is empty
    //     if ($batchData->isEmpty()) {
    //         return redirect()->route('stock.list')->with('error', 'No stock batches found for the selected product.');
    //     }

    //     // Group cartons under their respective batches and then by product
    //     $groupedData = $batchData->groupBy('product_id')->map(function ($items) {
    //         $product = $items->first(); // Common product details

    //         return [
    //             'product_id' => $product->product_id,
    //             'batches' => $items->groupBy('batch_id')->map(function ($batchItems) {
    //                 $batch = $batchItems->first(); // Common batch details for a specific batch

    //                 return [
    //                     'batch_id' => $batch->batch_id,
    //                     'batch_number' => $batch->batch_number,
    //                     'manufacturing_date' => $batch->manufacturing_date,
    //                     'expiry_date' => $batch->expiry_date,
    //                     'base_price' => $batch->base_price,
    //                     'exchange_rate' => $batch->exchange_rate,
    //                     'buy_price' => $batch->buy_price,
    //                     'notes' => $batch->notes,
    //                     'created_at' => $batch->created_at,
    //                     'updated_at' => $batch->updated_at,
    //                     'cartons' => $batchItems->map(function ($item) {
    //                         return [
    //                             'carton_id' => $item->carton_id,
    //                             'carton_number' => $item->carton_number,
    //                             'no_of_items_inside' => $item->no_of_items_inside,
    //                             'missing_items' => $item->missing_items,
    //                         ];
    //                     })->values(),
    //                 ];
    //             })->values(),
    //         ];
    //     });
    //     // dd($groupedData);
    //     return view('admin.edit', compact('groupedData'));
    // }

    public function show(string $id)
    {
      
        $product = Product::where('id', $id)->first();
        $brand = Brand::where('id', $product->brand_id)->first();
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
    //     $batches = $request->input('batches');

    //     if (!$batches || !is_array($batches)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid batches data.',
    //         ], 400);
    //     }

    //     foreach ($batches as $batch) {

    //         $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);

    //         // Update batch details
    //         // $batchModel->product_id = $batch['productId'] ?? null; // Ensure product ID is passed
    //         $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
    //         $batchModel->expiry_date = $batch['expiry_date'] ?: null;
    //         $batchModel->base_price = $batch['base_price'];
    //         $batchModel->exchange_rate = $batch['exchange_rate'];
    //         $batchModel->buy_price = $batch['buy_price'];
    //         // $batchModel->notes = $batch['notes'] ?: null;
    //         $batchModel->save();


    //         $cartonIds = [];
    //         foreach ($batch['cartons'] as $index => $carton) {
    //             if ($carton['missing_items'] > $carton['no_of_items_inside']) {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => "Missing items cannot be more than items inside for carton '{$batch['batchNo']}-" . ($index + 1) . "'."
    //                 ], 422);
    //             }
    //             $cartonModel = Carton::firstOrNew([
    //                 'batch_id' => $batchModel->id,
    //                 'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
    //             ]);

    //             $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
    //             $cartonModel->missing_items = $carton['missing_items'] ?: 0;
    //             $cartonModel->save();

    //             $cartonIds[] = $cartonModel->id; 
    //         }

    //         Carton::where('batch_id', $batchModel->id)
    //             ->whereNotIn('id', $cartonIds)
    //             ->delete();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Batches and cartons updated successfully.',
    //     ]);
    // }

    // public function update(Request $request)
    // {
    //     setDatabaseConnection();

    //     DB::beginTransaction();

    //     try {
    //         $batches = $request->input('batches');

    //         if (!$batches || !is_array($batches)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid batches data.',
    //             ], 400);
    //         }

    //         foreach ($batches as $batch) {
    //             $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);

    //             $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
    //             $batchModel->expiry_date = $batch['expiry_date'] ?: null;
    //             $batchModel->base_price = $batch['base_price'];
    //             $batchModel->exchange_rate = $batch['exchange_rate'];
    //             $batchModel->buy_price = $batch['buy_price'];
    //             $batchModel->save();

    //             $cartonIds = [];
    //             foreach ($batch['cartons'] as $index => $carton) {

    //                 if ($carton['missing_items'] > $carton['no_of_items_inside']) {
    //                     DB::rollBack();
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Missing items cannot be more than items inside for carton '{$batch['batch_no']}-" . ($index + 1) . "'."
    //                     ], 422);
    //                 }

    //                 $cartonModel = Carton::firstOrNew([
    //                     'batch_id' => $batchModel->id,
    //                     'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
    //                 ]);

    //                 $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
    //                 $cartonModel->missing_items = $carton['missing_items'] ?: 0;
    //                 $cartonModel->save();

    //                 $cartonIds[] = $cartonModel->id;
    //             }

    //             Carton::where('batch_id', $batchModel->id)
    //                 ->whereNotIn('id', $cartonIds)
    //                 ->delete();
    //         }

    //         DB::commit();

    //         config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
    //         DB::purge('pgsql');
    //         DB::connection('pgsql')->getPdo();

    //         DB::beginTransaction();

    //         foreach ($batches as $batch) {
    //             $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
    //             $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
    //             $batchModel->expiry_date = $batch['expiry_date'] ?: null;
    //             $batchModel->base_price = $batch['base_price'];
    //             $batchModel->exchange_rate = $batch['exchange_rate'];
    //             $batchModel->buy_price = $batch['buy_price'];
    //             $batchModel->save();

    //             $cartonIds = [];
    //             foreach ($batch['cartons'] as $index => $carton) {

    //                 if ($carton['missing_items'] > $carton['no_of_items_inside']) {
    //                     DB::rollBack();
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Missing items cannot be more than items inside for carton '{$batch['batch_no']}-" . ($index + 1) . "' in secondary database."
    //                     ], 422);
    //                 }

    //                 $cartonModel = Carton::firstOrNew([
    //                     'batch_id' => $batchModel->id,
    //                     'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
    //                 ]);

    //                 $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
    //                 $cartonModel->missing_items = $carton['missing_items'] ?: 0;
    //                 $cartonModel->save();

    //                 $cartonIds[] = $cartonModel->id;
    //             }

    //             Carton::where('batch_id', $batchModel->id)
    //                 ->whereNotIn('id', $cartonIds)
    //                 ->delete();
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Batches and cartons updated successfully.',
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while updating batches and cartons.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function update(Request $request)
    // {

    //     try {


    //         setDatabaseConnection();

    //         $batches = $request->input('batches');
    //         if (!$batches || !is_array($batches)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid batches data.',
    //             ], 400);
    //         }

    //         DB::beginTransaction();

    //         $historyDetails = [];
    //         //    dd($request->all());
    //         foreach ($batches as $batch) {
    //             $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
    //             $isNewBatch = !$batchModel->exists;
    //             $batchModel->product_id = $batch['product_id'] ?: null;
    //             $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
    //             $batchModel->expiry_date = $batch['expiry_date'] ?: null;
    //             $batchModel->base_price = $batch['base_price'];
    //             $batchModel->exchange_rate = $batch['exchange_rate'];
    //             $batchModel->buy_price = $batch['buy_price'];
    //             $batchModel->notes = $batch['notes'];
    //             $batchModel->save();

    //             $cartonIds = [];
    //             $batchDetails = [
    //                 'batch_number' => $batchModel->batch_number,
    //                 'product_id' => $batchModel->product_id,
    //                 'manufacturing_date' => $batchModel->manufacturing_date,
    //                 'expiry_date' => $batchModel->expiry_date,
    //                 'base_price' => $batchModel->base_price,
    //                 'exchange_rate' => $batchModel->exchange_rate,
    //                 'buy_price' => $batchModel->buy_price,
    //                 'is_new' => $isNewBatch,
    //                 'cartons' => [],
    //             ];

    //             foreach ($batch['cartons'] as $index => $carton) {
    //                 if ($carton['missing_items'] > $carton['no_of_items_inside']) {
    //                     DB::rollBack();
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Missing items cannot be more than items inside for carton '{$batch['batch_no']}-" . ($index + 1) . "'."
    //                     ], 422);
    //                 }

    //                 $cartonModel = Carton::firstOrNew([
    //                     'batch_id' => $batchModel->id,
    //                     'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
    //                 ]);

    //                 $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
    //                 $cartonModel->missing_items = $carton['missing_items'] ?: 0;
    //                 $cartonModel->save();

    //                 $cartonIds[] = $cartonModel->id;

    //                 // Add carton details to history
    //                 $batchDetails['cartons'][] = [
    //                     'carton_number' => $cartonModel->carton_number,
    //                     'no_of_items_inside' => $cartonModel->no_of_items_inside,
    //                     'missing_items' => $cartonModel->missing_items,
    //                 ];
    //             }

    //             // Remove cartons not present in the request
    //             Carton::where('batch_id', $batchModel->id)
    //                 ->whereNotIn('id', $cartonIds)
    //                 ->delete();

    //             $historyDetails[] = $batchDetails; // Add batch and cartons to history
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

            DB::beginTransaction();

            $historyDetails = [];
            // $invoice_number = $request->invoice;
            //    dd($request->all());
            foreach ($batches as $batch) {
                $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
                $isNewBatch = !$batchModel->exists;
                $batchModel->brand_id = $batch['brand_id'] ?: null;
                $batchModel->product_id = $batch['product_id'] ?: null;
                $batchModel->unit = $batch['unit'] ?: null;
                $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
                $batchModel->expiry_date = $batch['expiry_date'] ?: null;
                $batchModel->base_price = $batch['base_price'];
                $batchModel->exchange_rate = $batch['exchange_rate'];
                $batchModel->buy_price = $batch['buy_price'];
                $batchModel->no_of_units = $batch['no_of_units'];
                $batchModel->quantity = $batch['quantity'];
                $batchModel->invoice_no = $batch['invoice'];
                $batchModel->notes = $batch['notes'];
                $batchModel->save();

                $cartonIds = [];
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
                    'cartons' => [],
                ];

                // foreach ($batch['cartons'] as $index => $carton) {
                //     if ($carton['missing_items'] > $carton['no_of_items_inside']) {
                //         DB::rollBack();
                //         return response()->json([
                //             'success' => false,
                //             'message' => "Missing items cannot be more than items inside for carton '{$batch['batch_no']}-" . ($index + 1) . "'."
                //         ], 422);
                //     }

                //     $cartonModel = Carton::firstOrNew([
                //         'batch_id' => $batchModel->id,
                //         'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
                //     ]);

                //     $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
                //     $cartonModel->missing_items = $carton['missing_items'] ?: 0;
                //     $cartonModel->save();

                //     $cartonIds[] = $cartonModel->id;

                //     // Add carton details to history
                //     $batchDetails['cartons'][] = [
                //         'carton_number' => $cartonModel->carton_number,
                //         'no_of_items_inside' => $cartonModel->no_of_items_inside,
                //         'missing_items' => $cartonModel->missing_items,
                //     ];
                // }

                // // Remove cartons not present in the request
                // Carton::where('batch_id', $batchModel->id)
                //     ->whereNotIn('id', $cartonIds)
                //     ->delete();

                $historyDetails[] = $batchDetails; // Add batch and cartons to history
            }

            if (!empty($batch['hospitalPrice']) || !empty($batch['wholesalePrice']) || !empty($batch['retailPrice'])) {
                foreach ($batches as $batch) {
                    $batchModel = Sell::firstOrNew(['batch_no' => $batch['batch_no']]);
                    $batchModel->sku  = $batch['product_id'];
                    $batchModel->batch_no  = $batch['batch_no'];
                    $batchModel->hospital_price  = $batch['hospitalPrice'];
                    $batchModel->wholesale_price  = $batch['wholesalePrice'];
                    $batchModel->retail_price  = $batch['retailPrice'];
                    $batchModel->valid_from  = $batch['manufacturing_date'];
                    $batchModel->valid_to  = $batch['expiry_date'];
                    $batchModel->save();
                }
            }

            DB::commit();

            // Record update history
            Purchase_History::create([
                'action' => 'Batch and Carton Update',
                'details' => json_encode($historyDetails),
                'user_id' => auth()->id(),
                'batch_id' => $batchModel->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Batches and cartons updated successfully, and history recorded.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating batches: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating batches and cartons.',
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
}
