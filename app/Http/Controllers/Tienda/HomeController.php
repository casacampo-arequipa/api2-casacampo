<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tienda\HomeCollection;
use App\Models\Cottage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        $cottages = Cottage::all();
        return response()->json([
            'cottages' => new HomeCollection($cottages),
        ]);
    }

    public function Search(Request $request)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'adultos' => 'required|integer|min:1',
                'ninos' => 'required|integer|min:0',
                'habitaciones' => 'required|integer|min:1|max:4',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (($request->adultos + $request->ninos) > 16) {
            return response()->json([
                'message' => 'El total de personas (adultos + niños) no puede ser mayor a 16.'
            ], 422);
        }

        return response()->json([
            'Hola Mundo',
        ]);
    }
}
