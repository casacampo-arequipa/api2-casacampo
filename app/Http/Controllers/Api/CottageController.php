<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cottage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CottageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->package_id) {
            $package = Package::findOrFail($request->package_id);
            $price_monday_to_thursday = $package->price_monday_to_thursday;
            $price_friday_to_sunday = $package->price_friday_to_sunday;
            $garantia  = $package->guarantee;
            $clear  = $package->cleaning;

            $cottages = Cottage::all()->map(function ($cottage) use ($price_monday_to_thursday, $price_friday_to_sunday, $garantia, $clear) {
                $cottage->price_monday_to_thursday = $price_monday_to_thursday;
                $cottage->price_friday_to_sunday = $price_friday_to_sunday;
                $cottage->garantia =  $garantia;
                $cottage->clear = $clear;
                return $cottage;
            });
        } else {
            $cottages = Cottage::all();
        }
    }
    public function index2(Request $request)
    {
        // Si se proporciona un package_id en la solicitud
        if ($request->package_id) {
            // Obtén el paquete con el package_id especificado y maneja el error si no existe
            $package = Package::findOrFail($request->package_id);

            // Define el precio que deseas utilizar del paquete
            $price = $package->price_monday_to_thursday; // O usa otro campo de precio si es necesario

            // Obtén todas las cabañas y asigna el precio del paquete a cada una
            $cottages = Cottage::all()->map(function ($cottage) use ($price) {
                $cottage->price = $price; // Añade el precio del paquete a cada cabaña
                return $cottage;
            });
        } else {
            // Si no se especifica package_id, devuelve todas las cabañas sin precio adicional
            $cottages = Cottage::all();
        }

        return response()->json(['cottages' => $cottages]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'name_cottage' => 'required',
            'description' => 'required',
            'capacity' => 'required',
            'availability' => 'required',
            'rooms' => 'required',
            'beds' => 'required',
            'bathrooms' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cottage = Cottage::create($request->all());

        return response()->json(["cottage" => $cottage]);
    }
    public function update(Request $request, int $id)
    {
        $cottage = Cottage::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'name_cottage' => 'required',
            'description' => 'required',
            'capacity' => 'required',
            'availability' => 'required',
            'rooms' => 'required',
            'beds' => 'required',
            'bathrooms' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cottage->update($request->all());

        return response()->json(["cottage" => $cottage]);
    }

    public function destroy($id)
    {
        $package = Cottage::findOrFail($id);
        $package->delete();

        return response()->json(['message' => 'Cottage deleted successfully']);
    }
}
