<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellHistory;
use App\Models\SellCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (auth()->user()->cannot('view-sell')) {
            abort(403);
        }
        return view('admin.sell');
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
            if (auth()->user()->cannot('add-sell')) {
                abort(403);
            }
            setDatabaseConnection();
            //   Validate the incoming request data
            
            $validatedData = $request->validate([
                'sku' => 'required|string',
                'batch_no' => 'required|string',
                'hospital_price' => 'required|numeric',
                'wholesale_price' => 'required|numeric',
                'retail_price' => 'required|numeric',
                'valid_from' => 'required|date',
                'valid_to' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->valid_from && $value < $request->valid_from) {
                            $fail('The ' . $attribute . ' must be a date after or equal to the valid_from date.');
                        }
                    },
                ],
            ]);

            // Create the sell record
            $sell = Sell::create($validatedData);

            SellHistory::create([
                'batch_no' => $validatedData['batch_no'],
                'hospital_price' => $validatedData['hospital_price'],
                'wholesale_price' => $validatedData['wholesale_price'],
                'retail_price' => $validatedData['retail_price'],
                'valid_from' => $validatedData['valid_from'],
                'valid_to' => $validatedData['valid_to'],
                'user_id' => auth()->user()->id,
                'action' => 'create',
            ]);

            // config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
            // DB::purge('pgsql');
            // DB::connection('pgsql')->getPdo();

            // $sellSecondary = new Sell($validatedData); 
            // $sellSecondary->setConnection('pgsql'); 
            // $sellSecondary->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Sell record created successfully.',
                'data' => $sell,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected exceptions
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        return view('admin.sellList');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {

            if (auth()->user()->cannot('edit-sell')) {
                abort(403);
            }
            $products = Product::all();
            $productId = Product::findOrFail($id);

            setDatabaseConnection();

            DB::beginTransaction();

            $sell = Sell::where('sku', $productId->id)->first();
            $batches = [];

            if ($sell->batch_no) {

                $batchExists = Sell::where('batch_no', $sell->batch_no)
                    ->where('sku', '!=', $productId->id)
                    ->exists();
                if (!$batchExists) {
                    $batches = Batch::where('batch_number', $sell->batch_no)->get();
                }
            }


            return view('admin.sellEdit', compact('sell', 'products', 'batches'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            if (auth()->user()->cannot('edit-sell')) {
                abort(403);
            }

            setDatabaseConnection();

            DB::beginTransaction();

            $sell = Sell::findOrFail($id);

            $batch = Batch::where('batch_number', $request->batch_no)->first();

            if (!$batch) {
                return response()->json(['success' => false, 'message' => 'Invalid batch number.'], 422);
            }

            $buyPrice = $batch->buy_price;
            // Validate the incoming data
            $validatedData = $request->validate([
                'sku' => 'required|string',
                'batch_no' => 'required|string',
                'hospital_price' => ['required', 'numeric', 'min:' . $buyPrice],
                'wholesale_price' => ['required', 'numeric', 'min:' . $buyPrice],
                'retail_price' => ['required', 'numeric', 'min:' . $buyPrice],
                'valid_from' => 'required|date',
                'valid_to' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->valid_from && $value < $request->valid_from) {
                            $fail('The ' . $attribute . ' must be a date after or equal to the valid_from date.');
                        }
                    },
                ],
            ]);

            // Update the record
            $sell->update($validatedData);

            $sellHistory = SellHistory::create([
                'batch_no' => $validatedData['batch_no'],
                'hospital_price' => $validatedData['hospital_price'],
                'wholesale_price' => $validatedData['wholesale_price'],
                'retail_price' => $validatedData['retail_price'],
                'valid_from' => $validatedData['valid_from'],
                'valid_to' => $validatedData['valid_to'],
                'user_id' => auth()->user()->id,
                'action' => 'update',
            ]);

            if (!$sellHistory) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create sell history.',
                ], 500);
            }
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Sell record updated successfully.',
                'data' => $sell,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try {

            if (auth()->user()->cannot('delete-sell')) {
                abort(403);
            }
            // $products = Product::findOrFail($id);
            setDatabaseConnection();

            $sell = Sell::findOrFail($id);

            $sell->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sell record deleted successfully!',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getBatchesBySku($sku)
    {

        try {
            setDatabaseConnection();

            $product = Product::where('sku', $sku)->first();

            if ($product) {

                $batches = Batch::where('product_id', $product->id)->get();

                return response()->json([
                    'success' => true,
                    'message' => 'Batches fetched successfully.',
                    'batches' => $batches,
                ]);
            }

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

    public function getSellBatchesBySku($sku)
    {

        try {
            // setDatabaseConnection();

            $product = Product::where('id', $sku)->first();
            setDatabaseConnection();
            if ($product) {
                $batches = Batch::where('product_id', $product->id)
                    ->whereNotIn('batch_number', function ($query) use ($product) {
                        $query->select('batch_no')
                            ->from('sell')
                            ->where('product_id', $product->id);
                    })
                    ->get();

                return response()->json([
                    'success' => true,
                    'message' => 'Batches fetched successfully.',
                    'batches' => $batches,
                ]);
            }

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


    public function list(Request $request)
    {

        setDatabaseConnection();

        $sells = sell::orderBy('id', 'desc')->get();

        return view('admin.sellList', compact('sells'));
    }

    public function sellApprove(Request $request)
    {
        try {
            setDatabaseConnection();
    
            // Update status to true
            $updated = SellCounter::where('order_id', $request->order_id)
                ->update(['status' => true]);
            $updatedInvoice = Invoice::where('order_id', $request->order_id)
                ->update(['invoice_approved' => true]);
    
            if ($updated) {
                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'message' => 'Stock approved successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'success' => false,
                    'message' => 'No record found to update.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
}
