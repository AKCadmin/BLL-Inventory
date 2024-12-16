<?php

namespace App\Providers;

use App\Models\permission;
use App\Models\role;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // $this->registerPolicies();

        Gate::define('has-permission', function ($user, $permission) {
           
            $role = Role::where('role_name', $user->role)->first();
          
            if ($role) {
                $permissions = Permission::where(['role_id'=>$role->id,'status'=>1])->pluck('permission_name')->toArray();
              
                return in_array($permission, $permissions);
            }
            
            return false;
        });


        
    }
}
