<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Carton;
use App\Models\Product;
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

    public function store(Request $request)
    {
        try {
           
            // $databseName = Session::get('db_name');
            // config(['database.connections.pgsql.database' => 'bll_inventory']);
            // DB::purge('pgsql');
            // DB::connection('pgsql')->getPdo();
           
            

            // if (!$databseName) {
            //     return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
            // }
            // if($request->db_name){
               // $dbName = $databseName;
                // config(['database.connections.pgsql.database' => $dbName]);
                // DB::purge('pgsql');
                // DB::connection('pgsql')->getPdo();
            // }

            // $batches = json_decode($request->input('batches'), true);

            // $rules = [
            //     'SKU' => 'required|string|max:255',
            //     'batches' => 'required|array',
            //     'batches.*.batchNo' => 'required|string|max:255',
            //     'batches.*.manufacturingDate' => 'nullable|date',
            //     'batches.*.expiryDate' => 'nullable|date',
            //     'batches.*.basePrice' => 'required|numeric|min:0',
            //     'batches.*.exchangeRate' => 'required|numeric|min:0',
            //     'batches.*.buyPrice' => 'required|numeric|min:0',
            //     'batches.*.notes' => 'nullable|string',
            //     'batches.*.cartons' => 'required|array',
            //     'batches.*.cartons.*.itemsInside' => 'required|integer|min:0',
            //     'batches.*.cartons.*.missingItems' => 'nullable|integer|min:0',
            // ];

            // $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Validation errors.',
            //         'errors' => $validator->errors(),
            //     ], 422);
            // }
            $productSku = $request->input('SKU');
            $product = Product::where('sku', $productSku)->first();
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product with the given SKU not found.'], 404);
            }

            $batches = $request->input('batches');
            $decodedBatches = [];

            foreach ($batches as $batch) {
                $decodedBatches[] = json_decode($batch, true);
            }

            DB::beginTransaction();

            foreach ($decodedBatches as $batch) {
                $existingBatch = Batch::where('batch_number', $batch['batchNo'])->first();
                if ($existingBatch) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Batch number '{$batch['batchNo']}' already exists."
                    ], 409);
                }

                // Create batch
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

                // Create cartons
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
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Batches and cartons stored successfully.']);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            \Log::error('Error creating stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while creating the stock.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list()
    {
        $stocks = DB::table('batches')
            ->join('products', 'batches.product_id', '=', 'products.id')
            ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
            ->select(
                'products.sku',
                'batches.batch_number as batch_no',
                'batches.buy_price',
                DB::raw('COUNT(cartons.id) as cartons'),
                DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
                DB::raw('SUM(cartons.missing_items) as missing_items'),
                'batches.id as batch_id'
            )
            ->groupBy('batches.id', 'products.sku', 'batches.batch_number', 'batches.buy_price')
            ->orderBy('batches.id', 'DESC')
            ->get();

            if(auth()->user()->role == 1){
                return view('admin.stockList', compact('stocks'));
            }
            else{
                return view('admin.list', compact('stocks'));
            }     
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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

    public function update(Request $request)
    {
        DB::beginTransaction(); 

        try {
            $batches = $request->input('batches');

            if (!$batches || !is_array($batches)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid batches data.',
                ], 400);
            }

            foreach ($batches as $batch) {
                $batchModel = Batch::firstOrNew(['batch_number' => $batch['batch_no']]);

                $batchModel->manufacturing_date = $batch['manufacturing_date'] ?: null;
                $batchModel->expiry_date = $batch['expiry_date'] ?: null;
                $batchModel->base_price = $batch['base_price'];
                $batchModel->exchange_rate = $batch['exchange_rate'];
                $batchModel->buy_price = $batch['buy_price'];
                $batchModel->save();

                $cartonIds = [];
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
                }

                Carton::where('batch_id', $batchModel->id)
                    ->whereNotIn('id', $cartonIds)
                    ->delete();
            }

            DB::commit(); 

            return response()->json([
                'success' => true,
                'message' => 'Batches and cartons updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); 

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
