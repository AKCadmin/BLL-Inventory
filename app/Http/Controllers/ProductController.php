<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
       
        return view('admin.product',compact('companies'));
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
           
            $validated = $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
                'sku' => 'required|unique:products,sku|max:50',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|max:50',
            ]);
    
          
            $product = new Product();
            $product->company_id = $request->company_id;
            $product->sku = $request->sku;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();
    
           
            // $company = Company::findOrFail($request->company_id);
            // $normalizedCompanyName = strtolower(str_replace(' ', '_', $company->name));
           
            // config(['database.connections.pgsql.database' => $normalizedCompanyName]);
    
            
            // DB::purge('pgsql');
            // DB::reconnect('pgsql');
    
            $secondaryProduct = new Product();
            $secondaryProduct->setConnection('pgsql'); 
            $secondaryProduct->sku = $request->sku;
            $secondaryProduct->name = $request->name;
            $secondaryProduct->description = $request->description;
            $secondaryProduct->status = $request->status;
            $secondaryProduct->save();
    
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
            $validated = $request->validate([
                'company_id' => 'required|integer',
                'sku' => 'required|unique:products,sku,' . $id . '|max:50',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required',
            ]);

            $product = Product::findOrFail($id);
            $product->company_id = $request->company_id;
            $product->sku = $request->sku;
            $product->name = $request->name;
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
}
