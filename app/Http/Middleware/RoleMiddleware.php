<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        if (auth('api')->user()->rol_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403); // Forbidden
        }

        return $next($request);
    }
}
// app/Http/Middleware/RoleMiddleware.php
