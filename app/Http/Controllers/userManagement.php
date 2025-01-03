<?php

namespace App\Http\Controllers;

use App\Models\AdvanceModuleMaster;
use App\Models\Deploy;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class userManagement extends Controller
{

    public function show()
    {


        // if (auth()->user()->cannot('view-user-management')) {
        //     abort(403); 
        // }
        $roles = role::orderBy('id', 'asc')->get();
        $organizations = Organization::all();
        $moduleusers = User::with('roles')->whereNot('id', Auth::user()->id)->orderBy('id', 'asc')->get();
        // dd($moduleusers[0]->roles->role_name);

        return view('usersManagement.index', compact('roles', 'moduleusers', 'organizations'));
    }

    public function create(Request $request)
    {
        // if (auth()->user()->cannot('add-user-management')) {
        //     abort(403); 
        // }
        $validatedData = $request->validate([
            'organization_id' => 'required|integer|exists:organizations,id',
            'role_id' => 'required|integer|exists:roles,id',
            'admin_username' => 'required|string|max:255|unique:users,username',
            'admin_firstname' => 'required|string|max:255',
            'admin_lastname' => 'required|string|max:255',
            'phone_number' => 'required|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required',
            'current_status' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $validatedData['admin_firstname'] . ' ' . $validatedData['admin_lastname'],
                'username' => $validatedData['admin_username'],
                'organization_id' => $validatedData['organization_id'],
                'role' => $validatedData['role_id'],
                'phone' => $validatedData['phone_number'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'is_verified' => $validatedData['current_status'],
                'is_activated' => $validatedData['current_status'],
            ]);

            DB::commit();

            if ($user) {
                return response()->json(['success' => true, 'user' => $request->input(), 'message' => 'User created successfully!']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while creating the user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function userMigration(Request $request)
    {

        $dbName = $request->db_name;
        $userData = $request->user;


        try {


            // Change database connection to the newly created PostgreSQL database
            config(['database.connections.pgsql.database' => $dbName]);
            DB::purge('pgsql');  // Purge the previous connection
            DB::reconnect('pgsql'); // Reconnect to the new database
            $currentDatabase = DB::connection('pgsql')->getDatabaseName();


            if ($currentDatabase !== $dbName) {
                return response()->json(['success' => false, 'message' => 'Failed to switch to the database: ' . $dbName]);
            }

            $usersMigrationPath = '/database/migrations/0001_01_01_000000_create_users_table.php';
            $permissionmasterMigrationPath = '/database/migrations/2024_08_09_014220_create_permissions_table.php';
            $rolesMigrationPath = '/database/migrations/2024_10_15_131421_roles.php';
            $permissionsMigrationPath = '/database/migrations/2024_08_09_014220_create_permissions_table.php';
            $companyMigrationPath = '/database/migrations/2024_12_03_101211_create_companies_table.php';
            $productMigrationPath = '/database/migrations/2024_12_03_101214_create_products_table.php';
            $batchMigrationPath = '/database/migrations/2024_12_03_101421_create_batches_table.php';
            $cartonsMigrationPath = '/database/migrations/2024_12_03_101422_create_cartons_table.php';
            $sellMigrationPath = '/database/migrations/2024_12_07_113412_create_sell_table.php';
            $purchaseHistoryMigrationPath = '/database/migrations/2024_12_24_124745_create_purchase_history_table.php';


            $migrations = [
                $usersMigrationPath,
                $rolesMigrationPath,
                // $userRole,
                $permissionmasterMigrationPath,
                $permissionsMigrationPath,
                $companyMigrationPath,
                $productMigrationPath,
                $batchMigrationPath,
                $cartonsMigrationPath,
                $sellMigrationPath,
                $purchaseHistoryMigrationPath
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
            // DB::purge('pgsql');
            // DB::reconnect('pgsql');

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

    public function status(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:users,id',
                'status' => 'required|boolean',
            ]);

            $user = User::find($validatedData['id']);

            if ($user) {
                $user->is_activated = $validatedData['status'];
                $user->is_verified = $validatedData['status'];
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'User status updated successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating user status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while updating the user status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            // if (auth()->user()->cannot('edit-user-management')) {
            //     abort(403); 
            // }
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'user' => $user], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // if (auth()->user()->cannot('edit-user-management')) {
            //     abort(403); 
            // }
            $user = User::findOrFail($request->user_id);

            $validatedData = $request->validate([
                'organization_id' => 'required|integer|exists:organizations,id',
                'role_id' => 'required|string|exists:roles,id',
                'admin_username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'admin_firstname' => 'required|string|max:255',
                'admin_lastname' => 'required|string|max:255',
                'phone_number' => 'required|digits:10',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:6|same:confirm_password',
                'confirm_password' => 'nullable',
                'current_status' => 'required|boolean',
            ]);

            $user->update([
                'name' => $validatedData['admin_firstname'] . ' ' . $validatedData['admin_lastname'],
                'username' => $validatedData['admin_username'],
                'role' => $validatedData['role_id'],
                'phone' => $validatedData['phone_number'],
                'email' => $validatedData['email'],
                'is_verified' => $validatedData['current_status'],
                'is_activated' => $validatedData['current_status'],
                'status' => $validatedData['current_status'],
            ]);

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while updating the user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            // if (auth()->user()->cannot('delete-user-management')) {
            //     abort(403); 
            // }
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while deleting the user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {
            $roles = role::orderBy('id', 'asc')->get();
            $organizations = Organization::all();
            $moduleusers = User::with('roles')
                ->where('organization_id', '=', $request->company)
                ->whereNot('id', Auth::user()->id)
                ->orderBy('id', 'asc')->get();

            return response()->json(['data' => $moduleusers]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching users.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function listGet(Request $request)
    {
        try {

            $request->validate([
                'company' => 'required|integer|exists:organizations,id',
            ]);
            $users = User::where('organization_id', '=', $request->company)
                ->orderBy('id', 'asc')->get();
                
            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No users found for the specified organization.',
                ], 404);
            }
            return response()->json(['data' => $users]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching users.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
