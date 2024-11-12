<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PackegeController extends Controller
{
    public function index()
    {
        $packeges = Package::all();
        return response()->json(['packeges' => $packeges], 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'max_person' => 'required|integer',
            'price_monday_to_thursday' => 'required|numeric',
            'price_friday_to_sunday' => 'required|numeric',
            'cottage_ids' => 'array',
            'cottage_ids.*' => 'exists:cottages,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $package = Package::create($request->all());

        if ($request->has('cottage_ids')) {
            $package->cottages()->attach($request->input('cottage_ids'));
        }

        return response()->json(['message' => 'Package created successfully', 'package' => $package], 201);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'max_person' => 'required|integer',
            'price_monday_to_thursday' => 'required|numeric',
            'price_friday_to_sunday' => 'required|numeric',
            // 'cottage_ids' => 'array',
            // 'cottage_ids.*' => 'exists:cottages,id',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request->hasFile("img")) {
            if ($package->img) {
                Storage::delete($package->img);
            }
            $path = Storage::putFile("packages", $request->file("img"));
            $request->request->add(["img" => "storage/" . $path]);
        }


        $package->update($request->all());

        // Actualiza las cabañas asociadas si se envían en la solicitud
        if ($request->has('cottage_ids')) {
            $package->cottages()->sync($request->input('cottage_ids'));
        }

        return response()->json(['message' => 'Package updated successfully', 'package' => $package]);
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->cottages()->detach(); // Desvincula las cabañas del paquete antes de eliminarlo
        $package->delete();

        return response()->json(['message' => 'Package deleted successfully']);
    }
}
