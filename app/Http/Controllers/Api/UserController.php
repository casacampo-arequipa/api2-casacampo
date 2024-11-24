<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function index()
  {
    $users = User::all();
    return response()->json(['users' => $users], 200);
  }
  public function store(Request $request)
  {
    $validator = Validator::make(
      request()->all(),
      [
        'name' => 'required',
        'lastname' => 'required',
        'phone' => 'required',
        'email' => 'required|email|unique:users,email|max:255',
        'country' => 'string',
        'password' => 'required|string|min:8',
        'rol_id' => 'required|exists:rols,id'
      ]
    );
    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }
    $data = $request->all();
    $data['password'] = bcrypt($request->password);
    $user = User::create($data);

    return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user], 201);
  }
  public function update(Request $request, int $id)
  {
    try {
      $user = User::findOrFail($id);
      $validator = Validator::make(
        request()->all(),
        [
          'name' => 'required',
          'lastname' => 'required',
          'phone' => 'required',
          'email' => 'required|email|unique:users,email,' . $id . '|max:255',
          'country' => 'string',
          'password' => 'required|string|min:8',
          'rol_id' => 'required|exists:rols,id'
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors()->toJson(), 400);
      }
      $data = $request->all();
      $data['password'] = bcrypt($request->password);
      $user->update($data);

      return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user], 201);
    } catch (\Throwable $th) {
      //throw $th;
    }
  }
  public function destroy(int $id)
  {
    try {
      $user = User::findOrFail($id);
      if ($user->reservations()->exists()) {
        return response()->json([
          'message' => 'No se puede eliminar el usuario porque tiene reservas asociadas.'
        ], 400);
      }
      $user->delete();
      return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
    } catch (\Throwable $th) {
      //throw $th;
    }
  }
}
