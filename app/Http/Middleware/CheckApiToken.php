<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;


class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || !Cache::has('api_token_' . $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Optionally: you can set the user based on the token
        // $user = Cache::get('api_token_' . $token);
        // Auth::login($user); // Optional if you need to set the authenticated user

        return $next($request);
    }
}
