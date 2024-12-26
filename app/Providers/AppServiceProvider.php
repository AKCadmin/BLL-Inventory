<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // dd("hello");
        // $currentDatabase = DB::connection()->getDatabaseName();
        // config(['database.connections.pgsql.database' => "snowflack"]);
        // DB::purge('pgsql');
        // DB::connection('pgsql')->getPdo();
        // $currentDatabase = DB::connection()->getDatabaseName();

       
        $ViewPages = [
            'view-dashboard' => 1,
            'view-role' => 2,
            'view-permission-manager' => 3,
            'view-user-management' => 4,
            'view-company' => 5,
            'view-product' => 6,
            'view-stock' => 7,
            'view-purchase' => 8,
            'view-sell' => 9,
            'view-sell-counter' => 10,
            'view-order' => 11,
        ];

        $editPages = [
            'edit-dashboard' => 1,
            'edit-role' => 2,
            'edit-permission-manager' => 3,
            'edit-user-management' => 4,
            'edit-company' => 5,
            'edit-product' => 6,
            'edit-stock' => 7,
            'edit-purchase' => 8,
            'edit-sell' => 9,
            'edit-sell-counter' => 10,
            'edit-order' => 11,
        ];

        $addPages = [
            'add-dashboard' => 1,
            'add-role' => 2,
            'add-permission-manager' => 3,
            'add-user-management' => 4,
            'add-company' => 5,
            'add-product' => 6,
            'add-stock' => 7,
            'add-purchase' => 8,
            'add-sell' => 9,
            'add-sell-counter' => 10,
            'add-order' => 11,
        ];

        $deletePages = [
            'delete-dashboard' => 1,
            'delete-role' => 2,
            'delete-permission-manager' => 3,
            'delete-user-management' => 4,
            'delete-company' => 5,
            'delete-product' => 6,
            'delete-stock' => 7,
            'delete-purchase' => 8,
            'delete-sell' => 9,
            'delete-sell-counter' => 10,
            'delete-order' => 11,
        ];

        foreach ($ViewPages as $gate => $pageId) {
            Gate::define($gate, function ($user) use ($pageId) {
                if ($user->role === '1') {
                    return true; 
                }
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $currentDatabase = DB::connection()->getDatabaseName();
                // dd($currentDatabase);

                $permissions = DB::table('user_page_permissions')
                    ->where('user_id', $user->id)
                    ->where('page_id', $pageId)
                    ->first();

                if ($permissions && $permissions->page_permission) {
                    $permissionsArray = json_decode($permissions->page_permission, true);
                    return is_array($permissionsArray) && in_array('1', $permissionsArray, true);
                }

                return false;
            });
        }

        foreach ($addPages as $gate => $pageId) {
            Gate::define($gate, function ($user) use ($pageId) {
                if ($user->role === '1') {
                    return true; 
                }
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $currentDatabase = DB::connection()->getDatabaseName();

                $permissions = DB::table('user_page_permissions')
                    ->where('user_id', $user->id)
                    ->where('page_id', $pageId)
                    ->first();

                if ($permissions && $permissions->page_permission) {
                    $permissionsArray = json_decode($permissions->page_permission, true);
                    return is_array($permissionsArray) && in_array('2', $permissionsArray, true);
                }

                return false;
            });
        }

        foreach ($editPages as $gate => $pageId) {
            Gate::define($gate, function ($user) use ($pageId) {
                if ($user->role === '1') {
                    return true; 
                }
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $currentDatabase = DB::connection()->getDatabaseName();

                $permissions = DB::table('user_page_permissions')
                    ->where('user_id', $user->id)
                    ->where('page_id', $pageId)
                    ->first();

                if ($permissions && $permissions->page_permission) {
                    $permissionsArray = json_decode($permissions->page_permission, true);
                    return is_array($permissionsArray) && in_array('3', $permissionsArray, true);
                }

                return false;
            });
        }

        foreach ($deletePages as $gate => $pageId) {
            Gate::define($gate, function ($user) use ($pageId) {
                if ($user->role === '1') {
                    return true; 
                }
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $currentDatabase = DB::connection()->getDatabaseName();
                
                $permissions = DB::table('user_page_permissions')
                    ->where('user_id', $user->id)
                    ->where('page_id', $pageId)
                    ->first();

                if ($permissions && $permissions->page_permission) {
                    $permissionsArray = json_decode($permissions->page_permission, true);
                    return is_array($permissionsArray) && in_array('4', $permissionsArray, true);
                }

                return false;
            });
        }
    }
}
