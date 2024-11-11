<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::paginate(5);
        return response()->json(['discounts' => $discounts], 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'description' => 'required',
            'percentage' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'state' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $discount = Discount::create($request->all());

        return response()->json(["discount" => $discount],  200);
    }

    public function update(Request $request, int $id)
    {
        $discount = Discount::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'description' => 'required',
            'percentage' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'state' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $discount->update($request->all());

        return response()->json(["discount" => $discount],  200);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        $discount->delete();

        return response()->json(["discount" => "Descuento Borrado"], 200);
    }
}
