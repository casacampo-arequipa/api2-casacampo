<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tienda\HomeCollection;
use App\Models\Cottage;
use App\Models\Package;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        // $dateReservation = Reservation::select('date_start', 'date_end')->get();
        $cottages = Cottage::all();
        return response()->json([
            'cottages' => new HomeCollection($cottages),
            // 'datereserva' => $dateReservation
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
        };

        $reservationIds = Reservation::where(function ($query) use ($request) {
            $query->whereBetween('date_start', [$request->check_in, $request->check_out])
                ->orWhereBetween('date_end', [$request->check_in, $request->check_out])
                ->orWhere(function ($query) use ($request) {
                    $query->where('date_start', '<=', $request->check_in)
                        ->where('date_end', '>=', $request->check_out);
                });
        })->pluck('id');



        $reservedCottageIds = DB::table('cottage_reservation')
            ->whereIn('reservation_id', $reservationIds)
            ->pluck('cottage_id');

        $availableCottages = Cottage::whereNotIn('id', $reservedCottageIds)->get();
        $availableCottageIds = $availableCottages->pluck('id')->toArray();

        $packages = Package::where('max_person', '>=', $request->persons)
            ->whereHas('cottages', function ($query) use ($availableCottageIds) {
                $query->whereIn('cottages.id', $availableCottageIds);
            })
            ->with(['cottages' => function ($query) use ($availableCottageIds) {
                $query->whereIn('cottages.id', $availableCottageIds);
            }])
            ->orderBy('max_person', 'asc')
            ->first();
            
        return response()->json([
            'data' => $packages
        ]);
    }
}
