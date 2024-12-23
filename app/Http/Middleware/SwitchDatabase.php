<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SwitchDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->database_name) {
                // Switch to the user's database
                config(['database.connections.pgsql.database' => $user->database_name]);

                // Purge the old connection and establish the new one
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
            }
        }

        return $next($request);
    }
}
