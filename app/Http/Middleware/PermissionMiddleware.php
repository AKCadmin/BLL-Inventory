<?php

namespace App\Http\Middleware;

use App\Models\permission;
use App\Models\role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
       
       
        // $role = Role::where('role_name', $request->User_role)->first();

        // if (!$role) {
        //     abort(403, 'Unauthorized action.');
        // }

        // $permissions = Permission::where('role_id', $role->id)
        // ->pluck('permission_name')
        // ->toArray();
       
        // if (!in_array($permission, $permissions)) {
        //     abort(403, 'Unauthorized action.');
        // }
        $roleName = $request->User_role;

        // Use the Gate to check if the user has the required permission
        if (Gate::denies('has-permission', $permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
