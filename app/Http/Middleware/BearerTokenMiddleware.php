<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class BearerTokenMiddleware
{
    public function handle($request, Closure $next)
    {

        // echo "hi";
        $token = $request->bearerToken();

       
    //    dd(Cache::has('api_token' . $token));
        // if (Cache::has('api_token')) {
        //     return $next($request);
        // }
        if (!$token || !Cache::has('api_token_' . $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Optionally: you can set the user based on the token
        // $user = Cache::get('api_token_' . $token);
        // Auth::login($user); // Optional if you need to set the authenticated user

        return $next($request);
    }
}
