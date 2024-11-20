<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ],
            "user" => [
                "id" => auth('api')->user()->id,
                "name" => auth('api')->user()->name,
                "lastname" => auth('api')->user()->lastname,
                "role" => auth('api')->user()->rol->name_rol ?? 'Rol no asignado',
                "email" => auth('api')->user()->email,
                "pic" => auth('api')->user()->profile_photo_url
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
