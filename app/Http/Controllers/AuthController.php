<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        // Tomar todos los datos del Request y sobrescribir el password encriptado
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        // Crear el usuario
        $user = User::create($data);

        return response()->json($user, 201);
    }

    // public function login()
    // {
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth('api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token);
    // } --- en stand by

    public function logincookies()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Crear cookie segura
        $cookie = cookie(
            name: 'token',
            value: $token,
            minutes: JWTAuth::factory()->getTTL(), // duración en minutos
            path: '/',
            domain: '127.0.0.1', // o tu dominio
            secure: false, // true en producción con HTTPS
            httpOnly: false,
            raw: false,
            sameSite: 'Lax'
        );

        return $this->respondWithToken($token)->cookie($cookie);
    }


    public function me()
    {
        $user = auth('api')->user();
        $userInfo = [
            "name" => $user->name,
            "lastname" => $user->lastname,
            "phone" => $user->phone,
            "email" => $user->email,
            "country" => $user->country,
            "role" => auth('api')->user()->rol->name_rol ?? 'Rol no asignado',
            "profile_photo_path" => Str::contains(auth('api')->user()->profile_photo_path, 'storage/')
                ? auth('api')->user()->profile_photo_url
                : auth('api')->user()->profile_photo_path,
            "reservations" => $user->reservations->map(function ($reservation) {
                return [
                    "date_start" => $reservation->date_start,
                    "date_end" => $reservation->date_end,
                    "total_price" => $reservation->total_price,
                    "date_reservation" => $reservation->date_reservation,
                    "state" => $reservation->state,
                ];
            }),
        ];
        $numReservations = $user->reservations->count();
        $numOpinion = $user->opinions->count();
        return response()->json(["me" => $userInfo, "numres" => $numReservations, "numopinion" => $numOpinion]);
    }

    public function logout()
    {
        auth('api')->logout();
        $cookie = cookie()->forget('token');
        return response()->json(['message' => 'Successfully logged out'])->cookie($cookie);
    }

    public function setCookieFromToken(Request $request)
    {
        $token = $request->input('token');

        try {
            $cookie = cookie(
                name: 'token',
                value: $token,
                minutes: JWTAuth::factory()->getTTL(),
                path: '/',
                domain: null, // o tu dominio si estás en producción
                secure: false, // true en producción con HTTPS
                httpOnly: true,
                sameSite: 'Lax'
            );

            return response()->json(['message' => 'Cookie set', "user" => [
                "id" => auth('api')->user()->id,
                "name" => auth('api')->user()->name,
                "lastname" => auth('api')->user()->lastname,
                "role" => auth('api')->user()->rol->name_rol ?? 'Rol no asignado',
                "email" => auth('api')->user()->email,
                "profile_photo_path" => auth('api')->user()->profile_photo_path,
                "reservations" =>  auth('api')->user()->reservations->map(function ($reservation) {
                    return [
                        "date_start" => $reservation->date_start,
                        "date_end" => $reservation->date_end,
                        "total_price" => $reservation->total_price,
                        "date_reservation" => $reservation->date_reservation,
                        "state" => $reservation->state,
                    ];
                }),
            ],])->cookie($cookie);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "user" => [
                "id" => auth('api')->user()->id,
                "name" => auth('api')->user()->name,
                "lastname" => auth('api')->user()->lastname,
                "role" => auth('api')->user()->rol->name_rol ?? 'Rol no asignado',
                "email" => auth('api')->user()->email,
                "profile_photo_path" => Str::contains(auth('api')->user()->profile_photo_url, 'storage/')
                    ? auth('api')->user()->profile_photo_url
                    : auth('api')->user()->profile_photo_url,
                "reservations" =>  auth('api')->user()->reservations->map(function ($reservation) {
                    return [
                        "date_start" => $reservation->date_start,
                        "date_end" => $reservation->date_end,
                        "total_price" => $reservation->total_price,
                        "date_reservation" => $reservation->date_reservation,
                        "state" => $reservation->state,
                    ];
                }),
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
