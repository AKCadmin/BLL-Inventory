<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Carton;
use App\Models\Product;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Purchase_History;
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

        return view('stock.index');
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

            // Set the primary database connection
            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();

            $productSku = $request->input('SKU');
            $product = Product::where('name', $productSku)->first();
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
                $batchModel->manufacturing_date = $batch['manufacturingDate'] ?: null;
                $batchModel->expiry_date = $batch['expiryDate'] ?: null;
                $batchModel->base_price = $batch['basePrice'];
                $batchModel->exchange_rate = $batch['exchangeRate'];
                $batchModel->buy_price = $batch['buyPrice'];
                $batchModel->notes = $batch['notes'];
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



    public function list(Request $request)
    {

        $companies = Company::all();
        setDatabaseConnection();
        $stocks = DB::table('batches')
            ->join('products', 'batches.product_id', '=', 'products.id')
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

        if (auth()->user()->role == 1) {
            return view('admin.stockList', compact('stocks', 'companies'));
        } else {
            return view('admin.list', compact('stocks'));
        }
    }

    public function listByCompany(Request $request)
    {
        $organization = Organization::where('id', '=', $request->company)->first();
        $selectedDate = $request->selectedDate;
        $isToday = $selectedDate === now()->toDateString();
        // dd($isToday);
        // dd($selectedDate);
        // Dynamically set the database connection
        // dd($organization);
        config(['database.connections.pgsql.database' => $organization->name]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        // Build the query
        // $query = DB::table('batches')
        //     ->join('products', 'batches.product_id', '=', 'products.id')
        //     ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
        //     ->select(

        //         'products.name',
        //         'batches.batch_number as batch_no',
        //         'batches.buy_price',
        //         DB::raw('COUNT(cartons.id) as cartons'),
        //         DB::raw('COUNT(batches.id) as totalBatches'),
        //         DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
        //         DB::raw('SUM(cartons.missing_items) as missing_items'),
        //         'batches.id as batch_id',
        //         'products.status',
        //     )
        //     ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'products.name','products.status')
        //     ->orderBy('batches.id', 'DESC');

        // // Apply product filter if provided
        // if ($request->input('productId')) {
        //     $query->where('products.id', '=', $request->input('productId'));
        // }

        // // Execute the query and get the results
        // $stocks = $query->get();


        $query = DB::table('batches')
        ->join('products', 'batches.product_id', '=', 'products.id')
        ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
        ->leftJoinSub(
            DB::table('sell_carton')
                ->select(
                    'sell_carton.carton_id',
                    DB::raw('SUM(sell_carton.no_of_items_sell) as total_sold_items')
                )
                ->whereDate('sell_carton.created_at', '>', $selectedDate)
                ->groupBy('sell_carton.carton_id'),
            'sell_carton',
            'cartons.id',
            '=',
            'sell_carton.carton_id'
        )
        ->select(
            'products.name',
            'batches.batch_number as batch_no',
            'batches.buy_price',
            DB::raw('COUNT(cartons.id) as cartons'),
            DB::raw('COUNT(batches.id) as totalBatches'),
            DB::raw('COALESCE(SUM(cartons.no_of_items_inside), 0) as total_items'),
            DB::raw('SUM(cartons.missing_items) as missing_items'),
            DB::raw('COALESCE(SUM(sell_carton.total_sold_items), 0) as sold_items'),
            DB::raw('SUM(cartons.no_of_items_inside) + COALESCE(SUM(sell_carton.total_sold_items), 0) as available_items'),
            'batches.id as batch_id',
            'products.status'
        )
        ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price', 'products.name', 'products.status')
        ->orderBy('batches.id', 'DESC');
    
    // Apply product filter if provided
    if ($request->input('productId')) {
        $query->where('products.id', '=', $request->input('productId'));
    }
    

        // Apply date filter if selectedDate is provided
        // if ($selectedDate) {
        //     $query->whereDate('cartons.created_at', '<=', $selectedDate)
        //           ->where(function($subQuery) use ($selectedDate) {
        //               $subQuery->whereNull('sell_carton.created_at')
        //                        ->orWhereDate('sell_carton.created_at', '>', $selectedDate);
        //           });
        // }

        // Execute the query and get the results
        $stocks = $query->get();

        return response()->json($stocks);
    }


    public function listByProduct(Request $request)
    {

        // dd($request->input('company'));
        config(['database.connections.pgsql.database' => $request->input('company')]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();


        $companyId = Company::select('id')->where('name', '=', $request->input('company'))->first();
        $products = DB::table('products')
            ->where('products.company_id', '=', $companyId)
            ->get();

        return response()->json($products);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        setDatabaseConnection();

        $batchData = DB::table('batches')
            ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
            ->where('batches.id', $id)
            ->select(
                'batches.id as batch_id',
                'batches.batch_number',
                'batches.product_id',
                'batches.manufacturing_date',
                'batches.expiry_date',
                'batches.base_price',
                'batches.exchange_rate',
                'batches.buy_price',
                'batches.notes',
                'batches.created_at',
                'batches.updated_at',
                'cartons.id as carton_id',
                'cartons.carton_number',
                'cartons.no_of_items_inside',
                'cartons.missing_items'
            )
            ->get();

        // Check if batch data is empty
        if ($batchData->isEmpty()) {
            return redirect()->route('stock.list')->with('error', 'Stock batch not found.');
        }

        // Group cartons under a single batch
        $groupedData = $batchData->groupBy('batch_id')->map(function ($items) {
            $batch = $items->first(); // Common batch details

            return [
                'batch_id' => $batch->batch_id,
                'batch_number' => $batch->batch_number,
                'product_id' => $batch->product_id,
                'manufacturing_date' => $batch->manufacturing_date,
                'expiry_date' => $batch->expiry_date,
                'base_price' => $batch->base_price,
                'exchange_rate' => $batch->exchange_rate,
                'buy_price' => $batch->buy_price,
                'notes' => $batch->notes,
                'created_at' => $batch->created_at,
                'updated_at' => $batch->updated_at,
                'cartons' => $items->map(function ($item) {
                    return [
                        'carton_id' => $item->carton_id,
                        'carton_number' => $item->carton_number,
                        'no_of_items_inside' => $item->no_of_items_inside,
                        'missing_items' => $item->missing_items,
                    ];
                })->values(),
            ];
        });



        return view('admin.edit', compact('groupedData'));
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

            $historyDetails = []; // To store update history details

            foreach ($batches as $batch) {
                $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);
                $isNewBatch = !$batchModel->exists;

                $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
                $batchModel->expiry_date = $batch['expiry_date'] ?: null;
                $batchModel->base_price = $batch['base_price'];
                $batchModel->exchange_rate = $batch['exchange_rate'];
                $batchModel->buy_price = $batch['buy_price'];
                $batchModel->save();

                $cartonIds = [];
                $batchDetails = [
                    'batch_number' => $batchModel->batch_number,
                    'product_id' => $batchModel->product_id,
                    'manufacturing_date' => $batchModel->manufacturing_date,
                    'expiry_date' => $batchModel->expiry_date,
                    'base_price' => $batchModel->base_price,
                    'exchange_rate' => $batchModel->exchange_rate,
                    'buy_price' => $batchModel->buy_price,
                    'is_new' => $isNewBatch,
                    'cartons' => [],
                ];

                foreach ($batch['cartons'] as $index => $carton) {
                    if ($carton['missing_items'] > $carton['no_of_items_inside']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Missing items cannot be more than items inside for carton '{$batch['batch_no']}-" . ($index + 1) . "'."
                        ], 422);
                    }

                    $cartonModel = Carton::firstOrNew([
                        'batch_id' => $batchModel->id,
                        'carton_number' => $batch['batch_no'] . '-' . ($index + 1),
                    ]);

                    $cartonModel->no_of_items_inside = $carton['no_of_items_inside'];
                    $cartonModel->missing_items = $carton['missing_items'] ?: 0;
                    $cartonModel->save();

                    $cartonIds[] = $cartonModel->id;

                    // Add carton details to history
                    $batchDetails['cartons'][] = [
                        'carton_number' => $cartonModel->carton_number,
                        'no_of_items_inside' => $cartonModel->no_of_items_inside,
                        'missing_items' => $cartonModel->missing_items,
                    ];
                }

                // Remove cartons not present in the request
                Carton::where('batch_id', $batchModel->id)
                    ->whereNotIn('id', $cartonIds)
                    ->delete();

                $historyDetails[] = $batchDetails; // Add batch and cartons to history
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
        //
    }
}
