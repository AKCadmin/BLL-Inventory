<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Carton;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellCarton;
use App\Models\SellCounter;
use App\Models\CustomerTransaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SellCornerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->cannot('view-sell-counter')) {
            abort(403);
        }

        setDatabaseConnection();

        $sellList = DB::table('sell_counter as sc')
            ->select([
                'sc.order_id',
                'sc.customer_type',
                'sc.customer',
                DB::raw('SUM(sc.provided_no_of_cartons) as total_cartons'),
                DB::raw('SUM(sc.price) as total_price'),
                DB::raw('MIN(sc.created_at) as sale_date') // Assuming the earliest sale date represents the order
            ])
            ->groupBy('sc.order_id', 'sc.customer', 'sc.customer_type')
            ->get();

        // dd($sellList);

        return view('sellManagement.list', compact('sellList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::orderBy('id', 'desc')->get();
        if (auth()->user()->cannot('view-sell-counter')) {
            abort(403);
        }
        return view('admin.sellCounter', compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        // dd($request->all());
        if (auth()->user()->cannot('add-sell-counter')) {
            abort(403);
        }

        if ($request->has('organizationName')) {
            $databaseName = $request->organizationName;
        } else {
            $databaseName = Session::get('db_name');
        }

        $rules = [
            '*.customer' => 'required|string',
            '*.customerType' => 'required|string',
            '*.customerTypeName' => 'required|string',
            '*.rowIndex' => 'required|integer|min:0',
            '*.sku' => 'required|exists:products,id',
            '*.batchNo' => 'required|string',
            '*.unitsPerCarton' => 'required|integer|min:1',
            '*.availableQtyCarton' => 'required|integer|min:0',
            '*.packagingType' => 'required|array',
            '*.packagingType.byCarton' => 'required|boolean',
            '*.packagingType.quantity' => 'required|integer|min:1'
        ];

        $messages = [
            '*.customer.required' => 'Customer is required for all items',
            '*.customerType.required' => 'Customer type is required for all items',
            '*.sku.exists' => 'Invalid product SKU',
            '*.batchNo.required' => 'Batch number is required for all items',
            '*.packagingType.quantity.min' => 'Quantity must be at least 1',
            '*.unitsPerCarton.min' => 'Units per carton must be at least 1'
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction(); // Start a database transaction

        try {
            $data = $request->all();
            $totalAmount = 0;
            $sellCounterIds = [];
            $customerTypeName = "";
            $orderId = rand(100000, 999999);

            foreach ($data as $item) {
                $product = Product::findOrFail($item['sku']); // Use findOrFail for brevity
                // setDatabaseConnection(); // Consider moving this outside the loop if it's the same connection

                // Set the primary database connection
                config(['database.connections.pgsql.database' => $databaseName]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();

                $batchNumber = $item['batchNo'];
                $batch = Batch::where('id', $batchNumber)->firstOrFail();

                $sellPrice = Sell::where('batch_id', $batchNumber)->first();
                if (!$sellPrice) {
                    throw new \Exception("Price not found for batch number: {$batchNumber}");
                }

                $customerType = trim(strtolower(
                    preg_replace('/\s*\([^)]*\)/', '', $item['customerTypeName'])
                ));

                $customerTypeName = $customerType;

                $price = match ($customerType) {
                    'wholesale', 'wholesaler' => $sellPrice->wholesale_price,
                    'hospital' => $sellPrice->hospital_price,
                    'retail', 'retailer' => $sellPrice->retail_price,
                    default => throw new \Exception("Invalid customer type: {$customerType}")
                };

                // $quantity = $item['availableQtyCarton'] ?? 0;
                $quantity = $item['packagingType']['quantity'] ?? 0;
                $itemTotal = $quantity * $price;
                $totalAmount += $itemTotal;

                // Fetch customer
                $customer = Customer::where('id', $item['customer'])->first();

                if (!$customer) {
                    throw new \Exception("Customer not found: {$item['customer']}");
                }

                // Calculate the cost for the current item
                $itemTotal = $quantity * $price;

                // Ensure the customer has enough credit for this item
                if ($customer->credit_limit < $itemTotal) {
                    throw new \Exception("Insufficient credit limit for customer: {$item['customer']}");
                }

                $currentQuantity = (int)$batch->quantity;
                $sellCounter = new SellCounter();
                $sellCounter->company_id = 1; // Consider making this configurable
                $sellCounter->product_id = $item['sku'];
                $sellCounter->batch_id = $batch->id;
                $sellCounter->order_id = $orderId;
                $sellCounter->customer = $item['customer'];
                $sellCounter->customer_type = $customerType;
                $sellCounter->packaging_type = $item['packagingType']['byCarton']; // Check if byCarton exists
                // $sellCounter->provided_no_of_cartons = $currentQuantity; // Use $quantity directly
                $sellCounter->provided_no_of_cartons = $quantity; // Use $quantity directly
                $sellCounter->price = $itemTotal; // Store the individual item total, not cumulative
                $sellCounter->payment_status = $item['paymentStatus'];
                $sellCounter->save();
                $sellCounterIds[] = $sellCounter->id;

                $newQuantity = $currentQuantity - $quantity;
                $batch->quantity = $newQuantity;
                // $newQuantity = $currentQuantity;
                // $batch->quantity = $newQuantity;
                $batch->save();

                // Fetch customer


                // Store the previous credit limit
                $previousCreditLimit =  $customer->credit_limit;

                // Deduct only this item's total from the customer's credit limit
                $customer->credit_limit -= $itemTotal;
                $customer->save();

                // Log the transaction
                CustomerTransaction::create([
                    'customer_id' => $customer->id,
                    'order_id' => $orderId,
                    'amount' => -$itemTotal, // Negative because it's a deduction
                    'transaction_type' => 'purchase',
                    'previous_credit_limit' => $previousCreditLimit,
                    'new_credit_limit' => $customer->credit_limit,
                    'description' => "Purchased {$quantity} cartons of Product ID {$item['sku']}"
                ]);
            }

            $invoice = new Invoice();
            $invoice->sell_id = end($sellCounterIds); // Get the last sell counter ID
            $invoice->customer_name = $data[0]['customer'];
            $invoice->customer_type = $customerTypeName;
            $invoice->invoice_number = rand(100000, 999999); // Consider making this more robust (e.g., using a sequence)
            $invoice->order_id = $orderId;
            $invoice->invoice_approved = false;
            $invoice->save();


            DB::commit();

            return response()->json(['message' => 'Sale completed successfully', 'invoice_id' => $invoice->id], 200); // Return success message and invoice ID

        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Extract SKU from batch data, handling multiple formats.
     */
    private function extractSKU($skuData)
    {
        if (is_array($skuData[0])) {
            return $skuData[0]['SKU'] ?? null;
        }
        return $skuData[0] ?? null;
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $orderId)
    {
        config(['database.connections.pgsql.database' => Session::get('db_name')]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        $sellCounterItems = DB::table('sell_counter')
            ->where('order_id', $orderId)
            ->join('batches', 'sell_counter.batch_id', '=', 'batches.id')
            ->where('sell_counter.deleted_at', null)
            // ->join('products', 'sell_counter.product_id', '=', 'products.id')
            ->select(
                'sell_counter.*',
                'batches.batch_number',
                'batches.quantity as batch_quantity',
                'batches.no_of_units',
            )
            ->get();

        $responseData = [];

        foreach ($sellCounterItems as $index => $item) {
            $availableQtyCarton = (int)($item->batch_quantity);

            $responseData[] = [
                'customer' => (string)$item->customer,
                'customerType' => (string)$item->customer,
                'customerTypeName' => "{$item->customer_type} (" . number_format($item->price, 2) . ")",
                'rowIndex' => $index,
                'sku' => (string)$item->product_id,
                'batchNo' => $item->batch_number,
                'unitsPerCarton' => (string)$item->no_of_units,
                'availableQtyCarton' => (string)$availableQtyCarton,
                'packagingType' => [
                    'byCarton' => $item->packaging_type == 1,
                    'quantity' => (string)$item->provided_no_of_cartons
                ]
            ];
        }
        // dd($responseData);

        return view('admin.sellCounterEdit', ['responseData' => $responseData], ['orderId' => $orderId]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $orderId)
    {

        if (auth()->user()->cannot('edit-sell-counter')) {
            abort(403);
        }

        DB::beginTransaction();

        try {

            setDatabaseConnection();

            $data = $request->all();
            $totalAmount = 0;
            $sellCounterIds = [];
            $customerTypeName = "";

            // Fetch all existing sell counters for this order
            $existingSellCounters = SellCounter::where('order_id', $orderId)->get();

            // Restore original batch quantities before applying updates
            foreach ($existingSellCounters as $existingCounter) {
                $batch = Batch::find($existingCounter->batch_id);
                if ($batch) {
                    $batch->quantity += $existingCounter->provided_no_of_cartons;
                    $batch->save();
                }
            }

            // Delete existing sell counters (they will be recreated with new values)
            SellCounter::where('order_id', $orderId)->delete();

            foreach ($data as $item) {
                // $product = Product::findOrFail($item['sku']);
                // setDatabaseConnection();

                $batchNumber = $item['batchNo'];
                $batch = Batch::where('batch_number', $batchNumber)->firstOrFail();

                $sellPrice = Sell::where('batch_no', $batchNumber)->first();
                if (!$sellPrice) {
                    throw new \Exception("Price not found for batch number: {$batchNumber}");
                }

                $customerType = trim(strtolower(
                    preg_replace('/\s*\([^)]*\)/', '', $item['customerTypeName'])
                ));

                $customerTypeName = $customerType;

                $price = match ($customerType) {
                    'wholesale', 'wholesaler' => $sellPrice->wholesale_price,
                    'hospital' => $sellPrice->hospital_price,
                    'retail', 'retailer' => $sellPrice->retail_price,
                    default => throw new \Exception("Invalid customer type: {$customerType}")
                };

                $quantity = $item['packagingType']['quantity'] ?? 0;
                $itemTotal = $quantity * $price;
                $totalAmount += $itemTotal;

                $currentQuantity = (int)$batch->quantity;
                // Uncomment if you want to enforce quantity validation
                // if ($quantity > $currentQuantity) {
                //     throw new \Exception("Requested quantity ({$quantity}) exceeds available quantity ({$currentQuantity}) for batch: {$batchNumber}");
                // }

                $sellCounter = new SellCounter();
                $sellCounter->company_id = 1;
                $sellCounter->product_id = $item['sku'];
                $sellCounter->batch_id = $batch->id;
                $sellCounter->order_id = $orderId;
                $sellCounter->customer = $item['customer'];
                $sellCounter->customer_type = $customerType;
                $sellCounter->packaging_type = true;
                $sellCounter->provided_no_of_cartons = $quantity;
                $sellCounter->price = $itemTotal;
                $sellCounter->payment_status = $item['paymentStatus'];
                $sellCounter->save();
                $sellCounterIds[] = $sellCounter->id;

                $newQuantity = $currentQuantity - $quantity;
                $batch->quantity = $newQuantity;
                $batch->save();
            }

            // Update the existing invoice
            $invoice = Invoice::where('order_id', $orderId)->first();
            if ($invoice) {
                $invoice->sell_id = end($sellCounterIds);
                $invoice->customer_name = $data[0]['customer'];
                $invoice->customer_type = $customerTypeName;
                $invoice->invoice_approved = false; // Reset approval status after update
                $invoice->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Sale updated successfully',
                'invoice_id' => $invoice->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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

    public function getSellcounterBatchesBySku($sku)
    {
        try {

            setdatabaseConnection();
            $batches = Sell::where('sku', '=', $sku)
                ->join('batches', 'sell.batch_id', '=', 'batches.id')
                ->where('batches.quantity', '!=', 0)
                ->orderBy('valid_to', 'ASC')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Batches fetched successfully.',
                'batches' => $batches,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No product found with the given SKU.',
                'batches' => [],
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sellProductDataGet(Request $request)
    {
        try {
            $productsList = Product::all();
            setDatabaseConnection();
            $sells = Sell::with('product')->get()->unique('sku');

            return response()->json(['products' => $sells]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSellcounterCartonsByBatch($batch)
    {
        try {
            setDatabaseConnection();
            $batchId = Batch::where('id', $batch)->first();
            // $cartons = Carton::where('batch_id', $batchId->id)->where('no_of_items_inside', '!=', 0)
            //     ->get();
            $cartons = Carton::where('batch_id', $batchId->id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'carton fetched successfully.',
                'cartons' => $cartons,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No product found with the given Batch.',
                'cartons' => [],
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function orderList()
    {
        if (auth()->user()->cannot('view-order')) {
            abort(403);
        }
        $orders = SellCounter::select(
            'sell_counter.order_id',
            'sell_counter.customer',
            'sell_counter.customer_type',
            DB::raw('SUM(sell_carton.no_of_items_sell) as total_items'),
            DB::raw('SUM(sell_counter.price) as invoice_total'),
            'invoice.invoice_number',
            'invoice.invoice_approved',
            'invoice.id as invoice_id'
        )
            ->leftJoin('sell_carton', 'sell_counter.id', '=', 'sell_carton.sell_id')
            ->whereNotNull('sell_counter.order_id')
            ->Join('invoice', DB::raw('CAST(sell_counter.order_id AS VARCHAR)'), '=', DB::raw('CAST(invoice.order_id AS VARCHAR)'))
            ->groupBy(
                'sell_counter.order_id',
                'sell_counter.customer',
                'sell_counter.customer_type',
                'invoice.invoice_number',
                'invoice.invoice_approved',
                'invoice.id'
            )
            ->orderBy('sell_counter.order_id', 'desc')
            ->get();

        return view('admin.orderList', compact('orders'));
    }

    public function editSellCounter($orderId)
    {
        if (auth()->user()->cannot('edit-sell-counter')) {
            abort(403);
        }
        // Step 1: Retrieve the order details based on the orderId
        $sellCounters = SellCounter::where('order_id', $orderId)->get();

        if ($sellCounters->isEmpty()) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Initialize the data structure
        $skuData = [
            'orderId' => $orderId,
            'customer' => [],
            'items' => []
        ];

        // Step 2: Retrieve customer details from the first sell counter (assuming all sell counters have the same customer)
        $skuData['customer'] = [
            'name' => $sellCounters->first()->customer,
            'type' => $sellCounters->first()->customer_type
        ];

        // Step 3: Loop through the sell counters and gather the necessary data
        foreach ($sellCounters as $sellCounter) {
            // Get the product SKU and batch number
            $product = Product::find($sellCounter->product_id);
            $sku = $product->sku; // Assuming SKU is stored on the product

            // Find related batches for this sell counter
            $batch = Batch::find($sellCounter->batch_id);

            // Prepare the item structure
            $item = [
                'sku' => $sku,
                'batch' => $batch->batch_number,
                'batches' => []
            ];

            // Step 4: Retrieve SellCartons related to this sell counter and format the data
            $sellCartons = SellCarton::where('sell_id', $sellCounter->id)->get();
            $packagingTypeFormatted = $this->formatPackagingType($sellCounter->packaging_type);
            foreach ($sellCartons as $sellCarton) {
                $carton = Carton::find($sellCarton->carton_id);
                $item['batches'][] = [
                    'batch_no' => $carton->carton_number,
                    'packaging_type' => $packagingTypeFormatted, // Assuming packaging_type is stored in SellCarton
                    'quantity' => $sellCarton->no_of_items_sell
                ];
            }

            // Add the item to the items array
            $skuData['items'][] = $item;
        }
        //  dd($skuData);
        // Step 5: Return the formatted data as a response
        return view('admin.sellCounterEdit', compact('skuData'));
    }

    private function formatPackagingType($packagingType)
    {
        $replacements = [
            'byCarton' => 'By Carton',
            'byItemBox' => 'By Item Box'
        ];

        return $replacements[$packagingType] ?? $packagingType;
    }

    public function getBatchData($batchId)
    {

        try {

            setdatabaseConnection();
            $batches = Batch::where('id', '=', $batchId)
                ->first();
            // dd($batches);
            return response()->json([
                'success' => true,
                'message' => 'Batches fetched successfully.',
                'batches' => $batches,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
