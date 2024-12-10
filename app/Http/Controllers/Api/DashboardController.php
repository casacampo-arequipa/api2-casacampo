<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Promotion;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function infoDashboard()
    {
        $numuser = User::count();
        $numreser = Reservation::count();
        $numpromo = Promotion::count();
        $ganancias = Reservation::all()->groupBy(function ($date) {
            return Carbon::parse($date->date_reservation)->format('Y-m'); // Agrupar por año y mes
        });
        $gananciasPorMes = [];

        foreach ($ganancias as $key => $reservas) {
            $totalGanancias = $reservas->sum('total_price'); // Suma las ganancias de cada grupo
            $gananciasPorMes[$key] = $totalGanancias; // Asigna el total al array de ganancias
        }
        $usuariosPorMes = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $reservasPorMes = Reservation::selectRaw('DATE_FORMAT(date_reservation, "%Y-%m") as mes')
            ->groupBy('mes')
            ->orderBy('mes')
            ->selectRaw('count(*) as total_reservas')
            ->get();
        $package = Package::withCount('reservations')->get();
        $paqueteConMasReservas = $package->mapWithKeys(function ($package) {
            return [$package->name => $package->reservations_count];
        })->toArray();

        $ultimasReservas = Reservation::with('package:id,name', 'user:id,name,lastname')->orderBy('date_reservation', 'desc')
            ->take(5)  // Limita a las 5 reservas más recientes
            ->get()
            ->map(function ($reservation) {
                // Agregar el mensaje basado en el estado
                $reservation->payment_status = $reservation->state == 1 ? 'Pagado' : ($reservation->state == 0 ? 'Falta pagar' : 'Estado desconocido');
                return $reservation;
            });
        
        $userConMasReservas = User::withCount('reservations')
            ->orderByDesc('reservations_count')
            ->take(5)  // Limita a los 5 usuarios con más reservas
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->name => $user->reservations_count];
            })
            ->toArray();
        $datereservs = Reservation::select('date_start', 'date_end')  // Seleccionamos solo las columnas necesarias
            ->get();
        return response()->json([
            "mesage" => 200,
            "cards" => ["promociones" => $numpromo, "reservas" => $numreser, "usuarios" => $numuser],
            "estadistica" => ["ganancias" => $gananciasPorMes, "tendusuarios" => $usuariosPorMes, "tendreservas" => $reservasPorMes],
            "masreservaspaquetes"  => $paqueteConMasReservas,
            "fivereservas"  => $ultimasReservas,
            "morereseruser" => $userConMasReservas,
            "datereservs" => $datereservs,
        ]);
    }
}
