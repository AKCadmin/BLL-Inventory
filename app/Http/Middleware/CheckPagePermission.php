<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPagePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission, $pageId)
    {
        // Check if the user has the 'view' permission for the given page
        if (Gate::denies($permission, $pageId)) {
            abort(403); // Deny access if the permission check fails
        }

        return $next($request);
    }
}
