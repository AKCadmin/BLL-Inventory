<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Sell;
use Illuminate\Http\Request;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
            // Validate the incoming request data
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
        $sell = Sell::findOrFail($id);
        $products = Product::all();  
        $batches = [];
        if ($sell->batch_no) {
           
            $batchExists = Sell::where('batch_no', $sell->batch_no)
                ->where('id', '!=', $id) 
                ->exists();
            if (!$batchExists) {
                $batches = Batch::where('batch_number', $sell->batch_no)->get();
            }
        }

        return view('admin.sellEdit', compact('sell', 'products', 'batches'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $sell = Sell::findOrFail($id);

            // Validate the incoming data
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

            // Update the record
            $sell->update($validatedData);

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
           
            $sell = Sell::findOrFail($id); 
           
            $sell->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Sell record deleted successfully!',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
          
            return response()->json([
                'status' => 'error',
                'message' => 'Sell record not found!',
            ], 404);
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
            
            $product = Product::where('sku', $sku)->first();

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


    public function list()
    {
        $sells = sell::orderBy('id','desc')->get();

        return view('admin.sellList', compact('sells'));
    }
}
