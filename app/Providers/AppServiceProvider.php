<?php
// app/Providers/AppServiceProvider.php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
        // Dynamically define gates based on user permissions
        Gate::before(function ($user, $ability) {
            // Fetch the user permissions (menus) based on their role
            $menusAccess = Permission::select('menus')->where('role_id', Auth::user()->role)->first();
            $menus = $menusAccess ? json_decode($menusAccess->menus, true) : [];

            // If the user has the permission, allow access
            if (in_array($ability, $menus)) {
                return true; // Grant access if permission exists
            }

            // Deny access if the permission is not found
            return false;
        });

        // Example: Define gates for all menu options
        $this->defineMenuGates();
    }

    /**
     * Define gates for all available menu options.
     */
    private function defineMenuGates()
    {
        $menus = [
            'dashboard',
            'settings',
            'role_management',
            'permission_manager',
            'user_management',
            'company',
            'product',
            'stock_list',
            'add_purchase',
            'purchase_list',
            'add_sell',
            'add_sell_counter',
            'sell_stock',
            'sell_list',
            'order_list',
        ];

        foreach ($menus as $menu) {
            Gate::define($menu, function ($user) use ($menu) {
                return in_array($menu, $this->getUserPermissions($user));
            });
        }
    }

    /**
     * Get the permissions of the user from the menus.
     */
    private function getUserPermissions($user)
    {
        $menusAccess = Permission::select('menus')->where('role_id', $user->role)->first();
        $menus = $menusAccess ? json_decode($menusAccess->menus, true) : [];
        return $menus;
    }
}
