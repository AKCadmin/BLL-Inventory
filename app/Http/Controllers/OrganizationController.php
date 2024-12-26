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

            $organization = new Organization();
            // $organization->brand_id = $request->brand_id;
            $organization->name = $request->organization_name;
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

            $usersMigrationPath = '\\database\\migrations\\0001_01_01_000000_create_users_table.php';
            // $userRole = '\\database\\migrations\\2024_08_08_003839_add_role_to_users_table.php';
            // $permissionsMigrationPath = '\\database\\migrations\\2024_08_09_014220_create_permissions_table.php';
            // $rolesMigrationPath = '\\database\\migrations\\2024_10_15_131421_roles.php';
            // $menuMigrationPath = '\\database\\migrations\\2024_08_24_222034_create_menu_table.php';
            // $companyMigrationPath = '\\database\\migrations\\2024_12_03_101211_create_companies_table.php';
            $productMigrationPath = '\\database\\migrations\\2024_12_03_101214_create_products_table.php';
            $batchMigrationPath = '\\database\\migrations\\2024_12_03_101421_create_batches_table.php';
            $cartonsigrationPath = '\\database\\migrations\\2024_12_03_101422_create_cartons_table.php';
            $sellMigrationPath = '\\database\\migrations\\2024_12_07_113412_create_sell_table.php';
            $sellCounterMigrationPath = '\\database\\migrations\\2024_12_11_082844_create_sell_counter_table.php';
            $sellCartonMigrationPath = '\\database\\migrations\\2024_12_11_083350_create_sell_carton_table.php';
            $invoiceMigrationPath = '\\database\\migrations\\2024_12_11_083432_create_invoice_table.php';
            $purchaseHistoryMigrationPath = '\\database\\migrations\\2024_12_24_124745_create_purchase_history_table.php';
            $sellHistoryMigrationPath = '\\database\\migrations\\2024_12_24_143637_create_sell_histories_table.php';


            $migrations = [
                $usersMigrationPath,
                // $rolesMigrationPath,
                // $userRole,
                // $menuMigrationPath,
                // $permissionsMigrationPath,
                // $companyMigrationPath,
                $productMigrationPath,
                $batchMigrationPath,
                $cartonsigrationPath,
                $sellMigrationPath,
                $sellCounterMigrationPath,
                $sellCartonMigrationPath,
                $invoiceMigrationPath,
                $purchaseHistoryMigrationPath,
                $sellHistoryMigrationPath
            ];

            foreach ($migrations as $migrationPath) {
                $exitCode = Artisan::call('migrate', [
                    '--path' => $migrationPath,
                    '--database' => 'pgsql',
                ]);

                if ($exitCode !== 0) {
                    return response()->json(['success' => false, 'message' => 'Failed to apply migrations.', 'error' => Artisan::output()]);
                }
            }
            DB::purge('pgsql');
            DB::reconnect('pgsql');

            // DB::connection('pgsql')->table('users')->insert([
            //     'name' => $userData['admin_firstname'] . ' ' . $userData['admin_lastname'],
            //     'username' => $userData['admin_username'],
            //     'role' => 'Admin',
            //     'phone' => $userData['phone_number'],
            //     'email' => $userData['email'],
            //     'password' => Hash::make($userData['password']),
            //     'status' => $userData['current_status'],
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);



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
                'brand_id' => 'required|integer',
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
                $products = Product::with('organization')
                ->whereHas('organization', function($query) use ($request) {
                    $query->where('id', '=', $request->company);
                })
                ->orderBy('id', 'desc')
                ->get();
            } else {
                setDatabaseConnection();
                // $products = Product::where('company_id', auth()->user()->organization_id)
                //     ->orderBy('id', 'desc')
                //     ->get();
                $products = Product::orderBy('id', 'desc')
                    ->get();
            }
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

    public function switchOrganization(Request $request)
    {
        try {

            $organizationId = $request->input('organization');

            // Fetch the database details for the selected organization
            $organization = DB::table('organizations')->where('id', $organizationId)->first();
            session(['db_name' => $organization->name]);
            // if (!$organization) {
            //     return redirect()->back()->with('error', 'Organization not found.');
            // }

            // // Set the database connection dynamically
            // Config::set('database.connections.dynamic', [
            //     'driver' => 'pgsql',
            //     'url' => env('DB_URL'),
            //     'host' => env('DB_HOST', '127.0.0.1'),
            //     'port' => env('DB_PORT', '5432'),
            //     'database' => $organization->name,
            //     'username' => env('DB_USERNAME', 'root'),
            //     'password' => env('DB_PASSWORD', ''),
            //     'charset' => env('DB_CHARSET', 'utf8'),
            //     'prefix' => '',
            //     'prefix_indexes' => true,
            //     'search_path' => 'public',
            //     'sslmode' => 'prefer',
            // ]);

            // // Use the dynamic connection
            // DB::purge('dynamic');
            // DB::setDefaultConnection('dynamic');

            // $currentDatabase = DB::select('SELECT current_database() AS name'); // For PostgreSQL
            // $currentDatabaseName = $currentDatabase[0]->name;

            // dd($currentDatabaseName);
            return response()->json(['message' => 'Switched to ' . $organization->name . ' database.']);
            // return redirect()->back()->with('success', 'Switched to ' . $organization->name . ' database.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
