<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        if (auth()->user()->cannot('view-product')) {
            abort(403); 
        }
        $companies = Organization::all();
        $products = Product::with('brand')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();
       
        return view('admin.product',compact('companies','products','brands'));
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

            if (auth()->user()->cannot('add-product')) {
                abort(403); 
            }

            $validated = $request->validate([
                'brand_id' => 'required|integer|exists:brands,id',
                 'company_id' => 'required|integer|exists:organizations,id',
                // 'sku' => 'required|unique:products,sku|max:50',
                'name' => 'required|string|max:255',
                'unit' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|max:50',
            ]);
    
            $brand = Brand::where('id',$request->brand_id)->first();
            $productName =  $request->name . " (". $brand->name . ")";
          
            $product = new Product();
            $product->brand_id = $request->brand_id;
             $product->company_id = $request->company_id;
            // $product->sku = $request->sku;
            $product->name = $productName;
            $product->unit = $request->unit;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully in both databases.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function show(string $id)
    {
        if (auth()->user()->cannot('view-product')) {
            abort(403); 
        }
        try {
           
            $products = Product::orderBy('id', 'desc')->get();
            
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productData()
     {
       
        try {
            
            $products = Product::orderBy('id', 'desc')->get();
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    // Edit product
    public function edit($id)
    {
      
        try {
            if (auth()->user()->cannot('edit-product')) {
                abort(403); 
            }
            $product = Product::find($id);

            if ($product) {
                return response()->json(['success' => true, 'product' => $product]);
            }

            return response()->json(['success' => false, 'message' => 'Product not found']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update product
    public function update(Request $request, string $id)
    {
        try {
            if (auth()->user()->cannot('edit-product')) {
                abort(403); 
            }
            $validated = $request->validate([
                'brand_id' => 'required|integer',
                 'company_id' => 'required|integer',
                // 'sku' => 'required|unique:products,sku,' . $id . '|max:50',
                'name' => 'required|string|max:255',
                'unit' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required',
            ]);

            $product = Product::findOrFail($id);
            $product->brand_id = $request->brand_id;
             $product->company_id = $request->company_id;
            // $product->sku = $request->sku;
            $product->name = $request->name;
            $product->unit = $request->unit;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();

            return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete product
    public function destroy($id)
    {
        try {
            if (auth()->user()->cannot('delete-product')) {
                abort(403); 
            }
            $product = Product::find($id);

            if ($product) {
                $product->delete();
                return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'Product not found']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required',
        ]);

        try {

            $Product = Product::findOrFail($request->id);
            $Product->status = $request->status;
            $Product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully.',
                'data' => $Product,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function productDataGetById(Request $request)
    {
        try{
            if($request->productId){
            $products = Product::where('id', $request->productId)->first();
            }
            if($request->brandId){
                $products = Product::where('brand_id', $request->brandId)->get();
            }
            return response()->json(['data' => $products]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching products.',
            'error' => $e->getMessage(),
        ], 500);
    }
    }
}
