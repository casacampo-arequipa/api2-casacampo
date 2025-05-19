<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tienda\HomeCollection;
use App\Models\Cottage;
use App\Models\Package;
use App\Models\Reservation;
use Carbon\Carbon;
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
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'persons' => 'required|integer|min:1|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

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

        $availableCottageIds = Cottage::whereNotIn('id', $reservedCottageIds)
            ->pluck('id')
            ->toArray();

        $package = Package::where('max_person', '>=', $request->persons)
            ->whereHas('cottages', fn($query) => $query->whereIn('cottages.id', $availableCottageIds))
            ->with(['cottages' => fn($query) => $query->whereIn('cottages.id', $availableCottageIds)])
            ->orderBy('max_person', 'asc')
            ->first();

        if ($package) {
            return response()->json([
                'data' => $package,
                'message' => 'Paquete disponible en las fechas seleccionadas.'
            ]);
        }

        // Sugerencias si no hay disponibilidad
        $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
        $packageIds = Package::where('max_person', '>=', $request->persons)->pluck('id');



        $suggestions = "";
        $maxAttempts = 30;

        foreach ($packageIds as $packageId) {
            $cottageIds = DB::table('cottage_packages')
                ->where('package_id', $packageId)
                ->pluck('cottage_id');

            for ($i = 1; $i <= $maxAttempts && ($suggestions) < 3; $i++) {
                $start = Carbon::parse($request->check_in)->addDays($i);
                $end = $start->copy()->addDays($nights);

                $conflicts = Reservation::where(function ($query) use ($start, $end) {
                    $query->whereBetween('date_start', [$start, $end])
                        ->orWhereBetween('date_end', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('date_start', '<=', $start)
                                ->where('date_end', '>=', $end);
                        });
                })->pluck('id');

                $occupied = DB::table('cottage_reservation')
                    ->whereIn('reservation_id', $conflicts)
                    ->pluck('cottage_id');

                $available = array_diff($cottageIds->toArray(), $occupied->toArray());
                $allPackages = Package::whereIn('id', $packageIds)
                    ->with(['cottages' => fn($query) => $query->whereIn('cottages.id', $available)])
                    ->get()
                    ->keyBy('id');
                if (count($available) > 0) {
                    $packageInfo = $allPackages[$packageId];

                    $suggestions = [
                        'package_name' => $packageInfo,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                    ];
                }
            }
        }

        return response()->json([
            'data' => null,
            'message' => 'No hay disponibilidad en las fechas seleccionadas.',
            'suggestions' => $suggestions
        ]);
    }
}
