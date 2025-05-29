<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 1) {
            $organizations = Organization::all();
            $salesUsers = Customer::with('organization')->get();
            return view('customer.index', compact('salesUsers', 'organizations'));
        }
        $organizations = Organization::where('name', session('db_name'))->first();
        $salesUsers = Customer::with('organization')->where('organization_id', $organizations->id)->get();

        return view('customer.index', compact('salesUsers', 'organizations'));
    }

    public function store(Request $request)
    {
        // dd($request->input('retail_shop'));
        try {

            $validator = Validator::make($request->all(), [
                // 'organization_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|digits:10',
                'address' => 'required|string|max:255',
                'credit_limit' => 'required|numeric|min:0',
                'payment_days' => 'required|numeric|min:0',
                'type_of_customer' => 'required|string|max:255',
                'sale_user_status' => 'required|boolean',
                'retail_shop' => 'nullable|boolean',
                'opening_balance' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }

            $dbName = session('db_name');

            $organizationId = null;
            if ($request->input('organization_id') == null) {

                $organization = Organization::where('name', $dbName)->first();

                if (!$organization) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Organization not found for the current database.',
                    ], 404);
                }

                $organizationId = $organization->id;
            } else {
                $organizationId = $request->input('organization_id');
            }

            $retail_shop = false;
            if ($request->input('retail_shop') == 1) {
                $retail_shop = true;
            }

            $customer = Customer::first();
            // dd(DB::connection()->getConfig());
            // dd($customer->getAttributes());
            
            $saleUser = Customer::create([
                'organization_id' => $organizationId,
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'credit_limit' => $request->input('credit_limit'),
                'payment_days' => $request->input('payment_days'),
                'type_of_customer' => $request->input('type_of_customer'),
                'sale_user_status' => $request->input('sale_user_status'),
                'retail_shop' => $retail_shop,
                'opening_balance' => $request->input('opening_balance'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'data' => $saleUser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing sale user.
     */
    public function update(Request $request, $id)
    {
        try {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                // 'organization_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|digits:10',
                'address' => 'required|string|max:255',
                'credit_limit' => 'required|numeric|min:0',
                'payment_days' => 'required|numeric|min:0',
                'type_of_customer' => 'required|string|max:255',
                'sale_user_status' => 'required|boolean',
                'retail_shop' => 'nullable|boolean',
                'opening_balance' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the SaleUser by ID
            $saleUser = Customer::find($id);

            if (!$saleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale user not found.',
                ], 404);
            }

            $dbName = session('db_name');

            $organizationId = null;
            if ($request->input('organization_id') == null) {

                $organization = Organization::where('name', $dbName)->first();

                if (!$organization) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Organization not found for the current database.',
                    ], 404);
                }

                $organizationId = $organization->id;
            } else {
                $organizationId = $request->input('organization_id');
            }

            $retail_shop = false;
            if ($request->input('retail_shop') == 1) {
                $retail_shop = true;
            }


            // Update the SaleUser record
            $saleUser->update([
                'organization_id' => $organizationId,
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'credit_limit' => $request->input('credit_limit'),
                'payment_days' => $request->input('payment_days'),
                'type_of_customer' => $request->input('type_of_customer'),
                'sale_user_status' => $request->input('sale_user_status'),
                'retail_shop' => $retail_shop,
                'opening_balance' => $request->input('opening_balance'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'data' => $saleUser,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500); // 500 Internal Server Error
        }
    }

    public function edit($id)
    {

        $customer = Customer::find($id);

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found',
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found.',
        ]);
    }



    public function customerList(Request $request)
    {
       
        try {
            $databaseName = $request->db_name;
          
            if (!$databaseName) {
                return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
            }
            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();

            if ($request->company) {

                $organization = Organization::where('id', $request->company)->first();
            } else {
                $organization = Organization::where('name', $databaseName)->first();
            }

            if ($request->has('customerId')) {
                $customers = Customer::where(['organization_id' => $organization->id, 'id' => $request->customerId])->get();
            } else {
                $customers = Customer::select('customers.*', 'organizations.name as organizationName')
                    ->join('organizations', 'customers.organization_id', '=', 'organizations.id')
                    ->where('customers.organization_id', $organization->id)->get();
                // dd($customers);
            }
            return response()->json(['customers' => $customers]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function retailCustomerList(Request $request)
    {
        try {
            $databaseName = Session::get('db_name');
            if (!$databaseName) {
                return response()->json(['success' => false, 'message' => 'Database name is required for insertion.'], 400);
            }
            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();

            if ($request->company) {

                $organization = Organization::where(['id' => $request->company])->first();
            } else {
                $organization = Organization::where(['name' => $databaseName])->first();
            }

            if ($request->has('customerId')) {
                $customers = Customer::where(['organization_id' => $organization->id, 'id' => $request->customerId, 'retail_shop' => true])->get();
            } else {
                $customers = Customer::select('customers.*', 'organizations.name as organizationName')
                    ->join('organizations', 'customers.organization_id', '=', 'organizations.id')
                    ->where(['customers.organization_id' => $organization->id, 'retail_shop' => true])->get();
                // dd($customers);
            }
            return response()->json(['customers' => $customers]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function detail(Customer $customer)
    {
       

        $transactions = CustomerTransaction::where('customer_id', $customer->id)
            ->orderBy('created_at', 'asc')
            ->get();

          

        return view('customer.detail', compact('customer', 'transactions'));
    }
}
