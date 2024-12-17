<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    
    // Store new company
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required',
                'company_address' => 'required',
                'company_email' => 'required|email',
                'phone_no' => 'required',
                'company_status' => 'required',
            ]);

            $company = new Company();
            $company->name = $request->company_name;
            $company->address = $request->company_address;
            $company->contact_email = $request->company_email;
            $company->phone_no = $request->phone_no;
            $company->status = $request->company_status;
            $company->save();
            // $databaseName = str_replace(' ', '_', strtolower($request->company_name));
            // \DB::statement("CREATE DATABASE \"$databaseName\"");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get companies
    public function index()
    {
        return view('company.index');
    }

    public function show(string $id)
    {
        try {
            $companies = Company::orderBy('id','desc')->get();
            return response()->json(['companies' => $companies]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productDataGet(Request $request) {
        try {
           
            // if (auth()->user()->role == 1) {
                $products = Product::with('company')->orderBy('id', 'desc')->get();
            // } else {
            //     $products = Product::where('company_id', auth()->user()->company_id)
            //                        ->orderBy('id', 'desc')
            //                        ->get();
            // }
            // dd($products);
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productDataForSaleGet(Request $request) {
        try {
                $products = Product::with('company')->orderBy('id', 'desc')->get();
           
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Edit company
    public function edit($id)
    {
        try {
            $company = Company::find($id);

            if ($company) {
                return response()->json(['success' => true, 'company' => $company]);
            }

            return response()->json(['success' => false, 'message' => 'Company not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {

            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_address' => 'required|string|max:500',
                'company_email' => 'required|email|max:255',
                'phone_no' => 'required',
                'company_status' => 'required',
            ]);

            $company = Company::findOrFail($id);

            $company->name = $request->company_name;
            $company->address = $request->company_address;
            $company->contact_email = $request->company_email;
            $company->phone_no = $request->phone_no;
            $company->status = $request->company_status;
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Delete company
    public function destroy($id)
    {
        try {
            $company = Company::find($id);

            if ($company) {
                $company->delete();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Company not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
