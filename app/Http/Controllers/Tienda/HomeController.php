<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tienda\HomeCollection;
use App\Models\Cottage;
use App\Models\Package;
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

    public function search(Request $request)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'persons' => 'required|integer|min:1|max:16',
                // 'ninos' => 'required|integer|min:0',
                // 'habitaciones' => 'required|integer|min:1|max:4',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request->persons > 16) {
            return response()->json([
                'message' => 'El total de personas (adultos + niños) no puede ser mayor a 16.'
            ], 422);
        }
        $package = Package::whereBetween('max_person', [$request->persons, $request->persons + 2])
            ->orderBy('max_person', 'asc')
            ->with('cottages')
            ->first();

        if ($package == 0) {
            // Buscar el más cercano superior aunque esté fuera del margen
            $closest = Package::where('max_person', '>=', $request->persons)
                ->orderBy('max_person', 'asc')
                ->with('cottages')
                ->first();

            if ($closest) {
                return response()->json([
                    'message' => 'No hay un paquete exacto, pero este es el más cercano disponible.',
                    'data' => $closest
                ]);
            }

            return response()->json([
                'message' => 'No se encontró ningún paquete disponible.'
            ], 404);
        }

        return response()->json([
            'data' => $package
        ]);
    }
}
