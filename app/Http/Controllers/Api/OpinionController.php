<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpinionController extends Controller
{
    public function index()
    {
        $opinions = Opinion::paginate(5);
        return response()->json(['opinions' => $opinions], 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'calification' => 'required',
            'date' => 'required',
            'coment' => 'required',
            'reservation_id' => 'required|exists:reservations,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $reservation = Reservation::where('id', $request->reservation_id)->where('user_id', $request->user_id)->first();;

        if (!$reservation) {
            return response()->json(['error' => 'Este usuario no realizó esta reservación.'], 403);
        }

        $opinion = Opinion::create($request->all());

        return response()->json(["opinions" => $opinion],  200);
    }

    public function update(Request $request, int $id)
    {
        $opinion = Opinion::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'calification' => 'required',
            'date' => 'required',
            'coment' => 'required',
            'reservation_id' => 'required|exists:reservations,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $reservation = Reservation::where('id', $request->reservation_id)->where('user_id', $request->user_id)->first();

        if (!$reservation) {
            return response()->json(['error' => 'Este usuario no realizó esta reservación.'], 403);
        }

        $opinion->update($request->all());

        return response()->json(["opinion" => $opinion],  200);
    }

    public function destroy($id)
    {
        $opinion = Opinion::findOrFail($id);

        $opinion->delete();

        return response()->json(["opinion" => "Opinón Borrada"], 200);
    }
}
