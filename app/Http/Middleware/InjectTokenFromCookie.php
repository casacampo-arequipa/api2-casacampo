<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->hasCookie('token')) {
            return response()->json([
                'message' => 'Token no encontrado en la cookie.'
            ], 401);
        }
        $token = $request->cookie('token');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        return $next($request);
    }
}
