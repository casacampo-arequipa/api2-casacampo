<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Busca el token JWT dentro de la cookie 'token'
        $token = $request->cookie('token');

        if ($token) {
            // Dile a JWTAuth que use ese token para autenticar
            JWTAuth::setToken($token);
        }

        return $next($request);
    }
}
