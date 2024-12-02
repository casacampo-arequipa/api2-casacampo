<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json(['promotions' => $promotions], 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'name_promotion' => 'required',
            'percentage' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'state' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $promotion = Promotion::create($request->all());

        return response()->json(["promotion" => $promotion],  200);
    }


    public function apply_promotion(Request $request)
    {
        $promotion = Promotion::where("name_promotion", $request->name_promotion)->where("state", 1)->where('date_start', '<=', now())
            ->where('date_end', '>=', now())->first();
        if (!$promotion) {
            return response()->json(["message" => 403, "message_text" => "LA PROMOCIÓN INGRESADO NO EXISTE"]);
        }
        return response()->json([
            "message" => 200,
            "PROMO" => $promotion,
            "message_text" => "LA PROMOCIÓN ES VÁLIDA"
        ]);
    }

    public function update(Request $request, int $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'name_promotion' => 'required',
            'percentage' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'state' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $promotion->update($request->all());

        return response()->json(["promotion" => $promotion],  200);
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        $promotion->delete();

        return response()->json(["promotion" => "Promoción Borrada"], 200);
    }
}
