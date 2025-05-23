<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->user['given_name'] ?? $googleUser->getName(),
                'lastname' => $googleUser->user['family_name'] ?? '',
                'phone' => null,
                'country' => null,
                'email_verified_at' => $googleUser->user['email_verified'] ? now() : null,
                'password' => null,
                'google_id' => $googleUser->id,
                'profile_photo_path' => $googleUser->getAvatar(),
                'rol_id' => 2, // o cualquier ID de rol por defecto
            ]
        );

        Auth::login($user); // Laravel inicia sesión y crea la cookie
        // Autenticación con JWT

        $token = JWTAuth::fromUser($user);

        // Crear cookie JWT
        $cookie = cookie(
            name: 'token',
            value: $token,
            minutes: JWTAuth::factory()->getTTL(),
            path: '/',
            domain: '127.0.0.1', // o tu dominio en producción
            secure: false,
            httpOnly: false,
            raw: false,
            sameSite: 'Lax'
        );
        // Redirigir al frontend con cookie
        return redirect('http://127.0.0.1:5173')->withCookie($cookie);
    }

    // protected function respondWithToken($token)
    // {

    //     return redirect('http://127.0.0.1:5173')->response()->json([
    //         "user" => [
    //             "id" => auth('api')->user()->id,
    //             "name" => auth('api')->user()->name,
    //             "lastname" => auth('api')->user()->lastname,
    //             "role" => auth('api')->user()->rol->name_rol ?? 'Rol no asignado',
    //             "email" => auth('api')->user()->email,
    //             "profile_photo_path" => Str::contains(auth('api')->user()->profile_photo_url, 'storage/')
    //                 ? auth('api')->user()->profile_photo_url
    //                 : auth('api')->user()->profile_photo_url,
    //             "reservations" =>  auth('api')->user()->reservations->map(function ($reservation) {
    //                 return [
    //                     "date_start" => $reservation->date_start,
    //                     "date_end" => $reservation->date_end,
    //                     "total_price" => $reservation->total_price,
    //                     "date_reservation" => $reservation->date_reservation,
    //                     "state" => $reservation->state,
    //                 ];
    //             }),
    //         ],
    //     ], 200, [], JSON_PRETTY_PRINT)->cookie($cookie); // Redirige al frontend
    // }
}
