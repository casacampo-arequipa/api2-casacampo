<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cottage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CottageController extends Controller
{
    public function index()
    {
        $cottages = Cottage::all();

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
