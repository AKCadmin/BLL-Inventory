<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Deploy;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('id', 'desc')->get();
        $brands = Brand::all();

        return view('admin.organization', compact('organizations', 'brands'));
    }

    public function store(Request $request)
    {

        // return response()->json(['success' => true, 'db_name' => "Vardhaman Texttiles", 'user' => $request->input(), 'message' => 'User created successfully and database created!']);
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                // 'brand_id' => 'required',
                'organization_name' => 'required',
                'organization_address' => 'required',
                'organization_email' => 'required|email',
                'phone_no' => 'required',
                'organization_status' => 'required',
            ]);

            $organization_name = str_replace(' ', '_', strtolower($request->organization_name));

            $organization = new Organization();
            // $organization->brand_id = $request->brand_id;
            $organization->name = $organization_name;
            $organization->address = $request->organization_address;
            $organization->contact_email = $request->organization_email;
            $organization->phone_no = $request->phone_no;
            $organization->status = $request->organization_status;
            $organization->save();
            DB::commit();

            $databaseName = str_replace(' ', '_', strtolower($organization->name));
            $existingDeploy = Deploy::where('db_name', $databaseName)->first();

            if ($existingDeploy) {
                return response()->json(['success' => false, 'message' => 'A database with the same name already exists.']);
            }

            if (!$existingDeploy) {

                $deployTable = Deploy::create([
                    'user_id' => auth()->user()->id,
                    'db_name' => $databaseName,
                    'status' => 1,
                ]);

                try {

                    DB::statement("CREATE DATABASE \"$databaseName\"");
                    return response()->json(['success' => true, 'db_name' => $databaseName, 'user' => $request->input(), 'message' => 'User created successfully and database created!']);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the organization.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function organizationMigration(Request $request)
    {

        $dbName = $request->database_name;
        $databaseName = str_replace(' ', '_', strtolower($dbName));

        $userData = $request->user;


        try {

            config(['database.connections.pgsql.database' => $databaseName]);
            DB::purge('pgsql');
            DB::reconnect('pgsql');
            $currentDatabase = DB::connection('pgsql')->getDatabaseName();

            if ($currentDatabase !== $databaseName) {
                return response()->json(['success' => false, 'message' => 'Failed to switch to the database: ' . $dbName]);
            }

            
            $usersMigrationPath = 'database/migrations/0001_01_01_000000_create_users_table.php';
            $productMigrationPath = 'database/migrations/2024_12_03_101214_create_products_table.php';
            $batchMigrationPath = 'database/migrations/2024_12_03_101421_create_batches_table.php';
            $cartonMigrationPath = 'database/migrations/2024_12_03_101422_create_cartons_table.php';
            $sellMigrationPath = 'database/migrations/2024_12_07_113412_create_sell_table.php';
            $sellCounterMigrationPath = 'database/migrations/2024_12_11_082844_create_sell_counter_table.php';
            $sellCartonMigrationPath = 'database/migrations/2024_12_11_083350_create_sell_carton_table.php';
            $invoiceMigrationPath = 'database/migrations/2024_12_11_083432_create_invoice_table.php';
            $purchaseHistoryMigrationPath = 'database/migrations/2024_12_24_124745_create_purchase_history_table.php';
            $sellHistoryMigrationPath = 'database/migrations/2024_12_24_143637_create_sell_histories_table.php';
            $addBrandIdInProductsMigrationPath = 'database/migrations/2024_12_26_141516_add_brand_id_to_products_table.php';
            $modifyBatchesMigrationPath = 'database/migrations/2024_12_27_051546_modify_product_id_in_batches_table.php';
            $modifySellCartonMigrationPath = 'database/migrations/2024_12_27_051742_drop_foreign_key_from_product_id_in_batches_table.php';
            $updatesoftdeleteMigrationPath = 'database/migrations/2025_02_10_133512_create_softdelete_for_organization_table.php';
            // $customerRetailsMigrationPath = 'database/migrations/2025_02_03_071547_update_customer_table.php';
            $updatebatchMigrationPath = 'database/migrations/2025_02_03_082708_update_batch_table.php';
            


            $migrations = [
                $usersMigrationPath,

                $productMigrationPath,
                $batchMigrationPath,
                $cartonMigrationPath,
                $sellMigrationPath,
                $sellCounterMigrationPath,
                $sellCartonMigrationPath,
                $invoiceMigrationPath,
                $purchaseHistoryMigrationPath,
                $sellHistoryMigrationPath,
                $addBrandIdInProductsMigrationPath,
                $modifyBatchesMigrationPath,
                $modifySellCartonMigrationPath,
                $updatesoftdeleteMigrationPath,
                // $customerRetailsMigrationPath,
                $updatebatchMigrationPath

            ];

            foreach ($migrations as $migrationPath) {
                set_time_limit(60);
                
                $exitCode = Artisan::call('migrate', [
                    '--path' => $migrationPath,
                    '--database' => 'pgsql',
                    '--force' => true
                ]);
            
                if ($exitCode !== 0) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Failed to apply migrations.', 
                        'error' => Artisan::output()
                    ]);
                }
            }
            return response()->json(['success' => true, 'db_name' => $request->db_name, 'user' => $request->db_name, 'message' => 'Migrations for roles and permissions applied successfully on ' . $dbName]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to apply migrations.', 'error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $organization = Organization::find($id);

            if ($organization) {
                return response()->json(['success' => true, 'organization' => $organization]);
            }

            return response()->json(['success' => false, 'message' => 'organization not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the organization.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {

        try {
            $companies = Organization::orderBy('id', 'desc')->get();
            return response()->json(['companies' => $companies]);
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
                // 'brand_id' => 'required|integer',
                'organization_name' => 'required|string|max:255',
                'organization_address' => 'required|string|max:500',
                'organization_email' => 'required|email|max:255',
                'phone_no' => 'required',
                'organization_status' => 'required',
            ]);

            $organization = Organization::findOrFail($id);
            $organization->brand_id = $request->brand_id;
            $organization->name = $request->organization_name;
            $organization->address = $request->organization_address;
            $organization->contact_email = $request->organization_email;
            $organization->phone_no = $request->phone_no;
            $organization->status = $request->organization_status;
            $organization->save();

            return response()->json([
                'success' => true,
                'message' => 'organization updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the organization.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Delete organization
    public function destroy($id)
    {
        try {
            $organization = Organization::find($id);

            if ($organization) {
                $organization->delete();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'organization not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the organization.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productDataGet(Request $request)
    {
       
        try {

            if (auth()->user()->role == 1) {
               
                $products = Product::
                select('products.*','products.id as productId','brands.id','brands.name as brand_name','organizations.id','organizations.name as organization_name')
                ->join('brands','products.brand_id','=','brands.id')
                    // ->whereHas('brand', function($query) use ($request) {
                    //     $query->where('id', '=', $request->company);
                    // })
                    ->join('organizations', 'products.company_id', '=', 'organizations.id');
                    if (!empty($request->company)) {
                        $products->where('organizations.id', $request->company);
                    }
                    $products = $products->orderBy('productId', 'desc')->get();
                   
            } else {
                
                // setDatabaseConnection();
                $productBrand = Product::where('company_id', auth()->user()->organization_id)->first();
                $products = Product::where('brand_id', $productBrand->brand_id)->orderBy('id', 'desc')
                    ->get();
            }
           
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function switchOrganization(Request $request)
    {
        try {
            $organizationId = $request->input('organization');
    
            // Validate request
            if (!$organizationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Organization ID is required.',
                ], 400);
            }
    
            // Fetch organization
            $organization = DB::table('organizations')->where('id', $organizationId)->first();
    
            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Organization not found.',
                ], 404);
            }
           Artisan::call('optimize-clear');
            // Store in session
            session(['db_name' => $organization->name]);
            // Session::put('db_name', $organization->name);
            Session::put('organization_id', $organizationId);
    
            // Validate session
            $storedName = Session::get('db_name');
            $storedOrgId = Session::get('organization_id');
    
            if (!$storedName || !$storedOrgId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store session data.',
                ], 500);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Switched to ' . $storedName . ' database.',
                'stored_db_name' => $storedName,
                'stored_organization_id' => $storedOrgId,
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while switching organization.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
