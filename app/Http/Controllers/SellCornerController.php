<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Carton;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellCarton;
use App\Models\SellCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        if (auth()->user()->cannot('view-sell-counter')) {
            abort(403);
        }
        return view('admin.sellCounter');
    }

    /**
     * Store a newly created resource in storage.
     */


    // public function store(Request $request)
    // {
    //     $data = $request->all();
    //     dd($data);
    //     // Validate incoming data
    //     // $request->validate([
    //     //     'customer' => 'required|string',
    //     //     'skuData' => 'required|array',
    //     //     'skuData.*.SKU' => 'required|string|exists:products,sku',
    //     //     'skuData.*.customerType' => 'required|string',
    //     //     'skuData.*.packagingType' => 'required|string',
    //     //     'batchData' => 'required|array',
    //     //     'batchData.*.batch' => 'required|string|exists:batches,batch',
    //     //     'cartonData' => 'required|array',
    //     //     'cartonData.*.carton' => 'required|string|exists:cartons,carton_number',
    //     // ]);

    //     DB::beginTransaction();

    //     try {

    //         $totalQuantity = 0;
    //         foreach ($data['cartonItemData'] as $carton) {
    //             $totalQuantity += (int)$carton['quantityItem'];
    //         }

    //         // $sellPrice = Sell::where('sku', $data['skuData'][0]['SKU'])
    //         //     ->where('batch_no', $data['batchData'][0]['batch'])
    //         //     // ->where('valid_from', '<=', now())
    //         //     // ->where(function ($query) {
    //         //     //     $query->where('valid_to', '>=', now())
    //         //     //         ->orWhereNull('valid_to');
    //         //     // })
    //         //     ->first();

    //         // if ($sellPrice) {
    //         //     if (!empty($data['cartonData'])) {
    //         //         $numCartons = count($data['cartonData']);
    //         //         if ($data['skuData'][0]['customerType'] == 'wholesale') {
    //         //             $price =  $numCartons * $sellPrice->wholesale_price;
    //         //         } else if ($data['skuData'][0]['customerType'] == 'retailer') {
    //         //             $price = $numCartons * $sellPrice->retail_price;
    //         //         } else {
    //         //             $price = $numCartons * $sellPrice->hospital_price;
    //         //         }
    //         //     } 
    //         //     if (!empty($data['cartonItemData'])) {
    //         //         if ($data['skuData'][0]['customerType'] == 'wholesale') {
    //         //             $price = $totalQuantity * $sellPrice->wholesale_price;
    //         //         } else if ($data['skuData'][0]['customerType'] == 'retailer') {
    //         //             $price = $totalQuantity * $sellPrice->retail_price;
    //         //         } else {
    //         //             $price = $totalQuantity * $sellPrice->hospital_price;
    //         //         }
    //         //     }
    //         // } else {
    //         //     throw new \Exception("Sell data not found.");
    //         // }


    //         $product = Product::where('sku', $data['skuData'][0]['SKU'])->firstOrFail();
    //         $productId = $product->id;

    //         $batchIds = [];
    //         $batchNumber = [];
    //         foreach ($data['batchData'] as $batchItem) {
    //             $batch = Batch::where('batch_number', $batchItem['batch'])->firstOrFail();
    //             $batchIds[] = $batch->id;
    //             $batchNumber[] = $batch->id;
    //         }
    //         // dd($batchIds);
    //         $orderId = rand(100000, 999999);
    //         $sellCounterId = 0;
    //         $sellCounterIds = [];
    //         $ab = [];
    //         foreach ($batchIds as $key => $batchId) {
    //             // dd($batchId);
    //             $sellPrice = Sell::where('sku', $data['skuData'][0]['SKU'])
    //                 ->where('batch_no', $data['batchData'][$key]['batch'])
    //                 ->first();

    //             if ($sellPrice) {
    //                 // $numCartons = 0;
    //                 // $totalQuantity = 0;

    //                 if (!empty($data['cartonData'])) {
    //                     $numCartons = count($data['cartonData']);
    //                     if ($data['skuData'][0]['customerType'] == 'wholesale') {
    //                         $price =  100 * $sellPrice->wholesale_price;
    //                     } else if ($data['skuData'][0]['customerType'] == 'retailer') {
    //                         $price = 100 * $sellPrice->retail_price;
    //                     } else {
    //                         $price = 100 * $sellPrice->hospital_price;
    //                     }
    //                 } else {
    //                     if ($data['skuData'][0]['customerType'] == 'wholesale') {
    //                         $price = $totalQuantity * $sellPrice->wholesale_price;
    //                     } else if ($data['skuData'][0]['customerType'] == 'retailer') {
    //                         $price = $totalQuantity * $sellPrice->retail_price;
    //                         echo $totalQuantity;
    //                     } else {
    //                         $price = $totalQuantity * $sellPrice->hospital_price;
    //                     }
    //                 }
    //                 $ab[] = $price;

    //                 // $sellCounter = new SellCounter();
    //                 // $sellCounter->company_id = 1;
    //                 // $sellCounter->product_id = $productId;
    //                 // $sellCounter->batch_id = $batchId;
    //                 // $sellCounter->order_id = $orderId;
    //                 // $sellCounter->customer = $data['customer'];
    //                 // $sellCounter->customer_type = $data['skuData'][0]['customerType'];
    //                 // $sellCounter->packaging_type = $data['skuData'][0]['packagingType'];
    //                 // $sellCounter->provided_no_of_cartons = count($data['cartonData']) ? count($data['cartonData']) : count($data['cartonItemData']);
    //                 // $sellCounter->price = $price;
    //                 // $sellCounter->save();

    //                 // $sellCounterId = $sellCounter->id;

    //             }
    //         }

    //         dd($ab);
    //         $invoice = new Invoice();
    //         $invoice->sell_id = $sellCounterId;
    //         $invoice->customer_name = $data['customer'];
    //         $invoice->customer_type = $data['skuData'][0]['customerType'];
    //         $invoice->invoice_number = rand(100000, 999999);
    //         $invoice->invoice_approved = false;
    //         $invoice->save();

    //         foreach ($data['cartonData'] as $carton) {
    //             if (!empty($carton)) {
    //                 $cartonRecord = Carton::where('carton_number', $carton['carton'])->firstOrFail();

    //                 if ($cartonRecord->no_of_items_inside <= 0) {
    //                     throw new \Exception("Carton {$carton['carton']} does not have enough items.");
    //                 }

    //                 $cartonRecord->no_of_items_inside = max(0, $cartonRecord->no_of_items_inside - 100);
    //                 $cartonRecord->save();
    //                 $noOfCartons = count($data['cartonData']);
    //                 $sellCarton = new SellCarton();
    //                 $sellCarton->sell_id = $sellCounterId;
    //                 $sellCarton->carton_id = $cartonRecord->id;
    //                 $sellCarton->no_of_cartons = 1;
    //                 $sellCarton->no_of_items_sell = 100;
    //                 $sellCarton->save();
    //             }
    //         }

    //         foreach ($data['cartonItemData'] as $cartonItemData) {
    //             if (!empty($cartonItemData)) {
    //                 $carton = Carton::where('carton_number', $cartonItemData['cartonItem'])->firstOrFail();

    //                 if ($cartonItemData['quantityItem'] > $carton->no_of_items_inside) {
    //                     throw new \Exception("Carton {$cartonItemData['cartonItem']} does not have enough items. Available: {$carton->no_of_items_inside}, Requested: {$cartonItemData['quantityItem']}");
    //                 }

    //                 $carton->no_of_items_inside -= $cartonItemData['quantityItem'];
    //                 $carton->save();

    //                 $sellCarton = new SellCarton();
    //                 $sellCarton->sell_id = $sellCounterId;
    //                 $sellCarton->carton_id = $carton->id;
    //                 $sellCarton->no_of_cartons = 1;
    //                 $sellCarton->no_of_items_sell = $cartonItemData['quantityItem'];
    //                 $sellCarton->save();
    //             }
    //         }


    //         DB::commit();

    //         return response()->json(['message' => 'Sell data stored successfully!',]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function store(Request $request)
    {
        
        // dd($request->all());
        if (auth()->user()->cannot('add-sell-counter')) {
            abort(403);
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
                setDatabaseConnection(); // Consider moving this outside the loop if it's the same connection

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
                // if ($quantity >= $currentQuantity) {
                //     throw new \Exception("Requested quantity ({$quantity}) exceeds available quantity ({$currentQuantity}) for batch: {$batchNumber}");
                // }

                $sellCounter = new SellCounter();
                $sellCounter->company_id = 1; // Consider making this configurable
                $sellCounter->product_id = $item['sku'];
                $sellCounter->batch_id = $batch->id;
                $sellCounter->order_id = $orderId;
                $sellCounter->customer = $item['customer'];
                $sellCounter->customer_type = $customerType;
                $sellCounter->packaging_type = $item['packagingType']['byCarton']; // Check if byCarton exists
                $sellCounter->provided_no_of_cartons = $quantity; // Use $quantity directly
                $sellCounter->price = $itemTotal; // Store the individual item total, not cumulative
                $sellCounter->save();
                $sellCounterIds[] = $sellCounter->id;


                $newQuantity = $currentQuantity - $quantity;
                $batch->quantity = $newQuantity;
                $batch->save();
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
            ->where('sell_counter.deleted_at',null)
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

        return view('admin.sellCounterEdit', ['responseData' => $responseData],['orderId'=>$orderId]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {

    //     if (auth()->user()->cannot('edit-sell-counter')) {
    //         abort(403);
    //     }
    //     // Fetch all rows with the same order_id

    //     DB::beginTransaction();

    //     try {
    //         // Fetch all incoming request data
    //         $data = $request->all();
    //         $totalQuantity = 0;

    //         // Calculate the total quantity
    //         foreach ($data['batchData'] as $batchKey => $batchItem) {
    //             foreach ($batchItem['cartonItemsData'] as $cartonItem) {
    //                 $totalQuantity += (int) $cartonItem['quantityItem'];
    //             }
    //         }

    //         $product = Product::where('sku', $data['skuData'][0]['SKU'])->firstOrFail();
    //         $productId = $product->id;

    //         $sellCounterIds = [];
    //         $totalPrices = [];
    //         $price = 0;
    //         $ab = [];
    //         $xy = [];
    //         $xz = [];
    //         foreach ($data['batchData'] as $batchNumber => $batchItem) {
    //             $batch = Batch::where('batch_number', $batchNumber)->firstOrFail();
    //             $sellCounter = SellCounter::where(['order_id' => $id, 'batch_id' => $batch->id])->first();
    //             $skuData = $batchItem['sku'];
    //             $sku = is_array($skuData) ? $this->extractSKU($skuData) : $skuData;

    //             $sellPrice = Sell::where('sku', $sku)
    //                 ->where('batch_no', $batchNumber)
    //                 ->first();

    //             if (!$sellPrice) {
    //                 throw new \Exception("Sell price not found for SKU: {$sku} and Batch: {$batchNumber}");
    //             }





    //             if (!empty($batchItem['cartonData'])) {
    //                 $price = $data['skuData'][0]['customerType'] === 'wholesale'
    //                     ? 100 * $sellPrice->wholesale_price
    //                     : ($data['skuData'][0]['customerType'] === 'retailer'
    //                         ? 100 * $sellPrice->retail_price
    //                         : 100 * $sellPrice->hospital_price);
    //             } elseif (!empty($batchItem['cartonItemsData'])) {
    //                 $price = $data['skuData'][0]['customerType'] === 'wholesale'
    //                     ? $totalQuantity * $sellPrice->wholesale_price
    //                     : ($data['skuData'][0]['customerType'] === 'retailer'
    //                         ? $totalQuantity * $sellPrice->retail_price
    //                         : $totalQuantity * $sellPrice->hospital_price);
    //             }

    //             // Iterate through each sellCounter row and update it
    //             //   foreach ($sellCounters as $sellCounter) {

    //             $sellCounter->batch_id = $batch->id;
    //             $sellCounter->product_id = $productId;
    //             $sellCounter->price = $price;
    //             $sellCounter->customer_type = $data['skuData'][0]['customerType'];
    //             $sellCounter->provided_no_of_cartons = count($batchItem['cartonData']) ?: count($batchItem['cartonItemsData']);
    //             $ab[] = $sellCounter;
    //             //   $sellCounter->save();


    //             $sellCounterIds[] = $sellCounter->id;
    //             $totalPrices[] = $price;

    //             // Update carton and carton items logic
    //             if (!empty($batchItem['cartonData'] ?? [])) {
    //                 foreach ($batchItem['cartonData'] as $cartonData) {
    //                     $carton = Carton::where('carton_number', $cartonData)->firstOrFail();
    //                     $carton->no_of_items_inside = max(0, $carton->no_of_items_inside - 100);


    //                     $sellCarton = SellCarton::where('order_id', $id)->first();
    //                     $sellCarton->sell_id = $sellCounter->id;
    //                     $sellCarton->carton_id = $carton->id;
    //                     $sellCarton->no_of_cartons = 1;
    //                     $sellCarton->no_of_items_sell = 100;
    //                     $sellCarton->order_id = $id;
    //                     // $sellCarton->save();
    //                     $xy[] = $sellCarton;
    //                     //   $carton->save();
    //                 }
    //             }

    //             if (!empty($batchItem['cartonItemsData'] ?? [])) {
    //                 foreach ($batchItem['cartonItemsData'] as $cartonItem) {
    //                     $carton = Carton::where('carton_number', $cartonItem['cartonItem'])->firstOrFail();

    //                     if ($cartonItem['quantityItem'] > $carton->no_of_items_inside) {
    //                         throw new \Exception("Carton {$cartonItem['cartonItem']} does not have enough items. Available: {$carton->no_of_items_inside}, Requested: {$cartonItem['quantityItem']}");
    //                     }

    //                     $carton->no_of_items_inside -= $cartonItem['quantityItem'];

    //                     //   $carton->save();

    //                     $sellCarton = SellCarton::where('order_id', $id)->first();
    //                     $sellCarton->sell_id = $sellCounter->id;
    //                     $sellCarton->carton_id = $carton->id;
    //                     $sellCarton->no_of_cartons = 1;
    //                     $sellCarton->no_of_items_sell = (int)$cartonItem['quantityItem'];
    //                     $sellCarton->order_id = $id;
    //                     $xz[] = $sellCarton;
    //                     // $sellCarton->save();
    //                 }
    //             }
    //             //   }


    //         }
    //         dd($xz);
    //         // Update the invoice
    //         $invoice = Invoice::where('order_id', $request->orderId)->first();
    //         $invoice->customer_name = $request->customer;
    //         $invoice->customer_type = $data['skuData'][0]['customerType'];
    //         //   $invoice->save();

    //         DB::commit();

    //         return response()->json(['message' => 'Sell data updated successfully!']);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //         ], 500);
    //     }
    // }

    public function update(Request $request, $orderId)
    {
   // dd($request->all());
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
            // $products = Sell::distinct('batch_no')->get();
            // $sells = Sell::all()->unique('sku');
            // $mappedData = $sells->map(function ($sell) use ($productsList) {
            //     $product = $productsList->firstWhere('id', $sell->sku);
            //     return [
            //         'sku' => $sell->sku,
            //         'product_name' => $product ? $product->name : 'Unknown Product',
            //     ];
            // });
            //  dd($sells);
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
            $batchId = Batch::where('batch_number', $batch)->first();
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
            $batches = Batch::where('batch_number', '=', $batchId)
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
