<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Deploy;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    // Store new brand
    public function store(Request $request)
    {

        // return response()->json(['success' => true, 'db_name' => "Vardhaman Texttiles", 'user' => $request->input(), 'message' => 'User created successfully and database created!']);
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'brand_name' => 'required',
                'brand_address' => 'required',
                'brand_contact' => 'required',
                'phone_no' => 'required',
                'brand_status' => 'required',
                'brand_category' => 'required|string|max:255', 
                'brand_description' => 'nullable|string|max:1000',
            ]);

            $brand = new Brand();
            $brand->name = $request->brand_name;
            $brand->address = $request->brand_address;
            $brand->contact_person = $request->brand_contact;
            $brand->phone_no = $request->phone_no;
            $brand->category = $request->brand_category; 
            $brand->description = $request->brand_description;
            $brand->status = $request->brand_status;
            $brand->save();
            DB::commit();
            // $databaseName = str_replace(' ', '_', strtolower($brand->name));
            // $existingDeploy = Deploy::where('db_name', $databaseName)->first();

            // if ($existingDeploy) {
            //     return response()->json(['success' => false, 'message' => 'A database with the same name already exists.']);
            // }

            // if (!$existingDeploy) {

            //     $deployTable = deploy::create([
            //         'user_id' => auth()->user()->id,
            //         'db_name' => $databaseName,
            //         'status' => 1,
            //     ]);

            //     try {

            //         DB::statement("CREATE DATABASE \"$databaseName\"");
            //         return response()->json(['success' => true, 'db_name' => $databaseName, 'user' => $request->input(), 'message' => 'User created successfully and database created!']);
            //     } catch (\Exception $e) {
            //         return response()->json(['success' => false, 'message' => $e->getMessage()]);
            //     }
            // } else {
            //     return response()->json(['success' => true, 'db_name' => $databaseName, 'user' => $request->input(), 'message' => 'User created successfully and database created!', 'note' => 'already exist']);
            // }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the brand.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function brandMigration(Request $request)
    {

        $dbName = $request->db_name;
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
            // $brandMigrationPath = '\\database\\migrations\\2024_12_03_101211_create_companies_table.php';
            $productMigrationPath = '\\database\\migrations\\2024_12_03_101214_create_products_table.php';
            $batchMigrationPath = '\\database\\migrations\\2024_12_03_101421_create_batches_table.php';
            $cartonsigrationPath = '\\database\\migrations\\2024_12_03_101422_create_cartons_table.php';
            $sellMigrationPath = '\\database\\migrations\\2024_12_07_113412_create_sell_table.php';
            $sellCounterMigrationPath = '\\database\\migrations\\2024_12_11_082844_create_sell_counter_table.php';
            $sellCartonMigrationPath = '\\database\\migrations\\2024_12_11_083350_create_sell_carton_table.php';
            $invoiceMigrationPath = '\\database\\migrations\\2024_12_11_083432_create_invoice_table.php';


            $migrations = [
                $usersMigrationPath,
                // $rolesMigrationPath,
                // $userRole,
                // $menuMigrationPath,
                // $permissionsMigrationPath,
                // $brandMigrationPath,
                $productMigrationPath,
                $batchMigrationPath,
                $cartonsigrationPath,
                $sellMigrationPath,
                $sellCounterMigrationPath,
                $sellCartonMigrationPath,
                $invoiceMigrationPath
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


    // Get companies
    public function index()
    {
        $brands = brand::orderBy('id', 'desc')->get();

        return view('company.index', compact('brands'));
    }

    public function show(string $id)
    {

        try {
            $companies = Brand::orderBy('id', 'desc')->get();
            return response()->json(['companies' => $companies]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the brand.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productDataGet(Request $request)
    {
        try {

            if (auth()->user()->role == 1) {
                $products = Product::with('brand')->orderBy('id', 'desc')->get();
            } else {
                $products = Product::where('organization_id', auth()->user()->brand_id)
                    ->orderBy('id', 'desc')
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

    public function productDataForSaleGet(Request $request)
    {
        try {
            $products = Product::with('brand')->orderBy('id', 'desc')->get();

            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Edit brand
    public function edit($id)
    {
        try {
            $brand = Brand::find($id);

            if ($brand) {
                return response()->json(['success' => true, 'brand' => $brand]);
            }

            return response()->json(['success' => false, 'message' => 'brand not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the brand.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {

            $validated = $request->validate([
                'brand_name' => 'required|string|max:255',
                'brand_address' => 'required|string|max:500',
                'brand_contact' => 'required',
                'phone_no' => 'required',
                'brand_status' => 'required',
                'brand_category' => 'required|string|max:255', 
                'brand_description' => 'nullable|string|max:1000',
            ]);

            $brand = Brand::findOrFail($id);

            $brand->name = $request->brand_name;
            $brand->address = $request->brand_address;
            $brand->contact_person = $request->brand_contact;
            $brand->phone_no = $request->phone_no;
            $brand->status = $request->brand_status;
            $brand->category = $request->brand_category;
            $brand->description = $request->description;

            $brand->save();

            return response()->json([
                'success' => true,
                'message' => 'brand updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the brand.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Delete brand
    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);

            if ($brand) {
                $brand->delete();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'brand not found']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the brand.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
