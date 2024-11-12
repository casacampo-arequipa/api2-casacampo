<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cottage;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('cottage_id')) {
            $reservations = Reservation::with('package.cottages')
                ->whereHas('package.cottages', function ($query) use ($request) {
                    $query->where('cottages.id', $request->cottage_id);
                })
                ->get();
        } else {
            $reservations = Reservation::with('package.cottages')->get();
        }

        return response()->json(['reservations' => $reservations]);
    }

    public function store(Request $request)
    {
        // Validar los datos que vienen del formulario o API
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required|exists:users,id',
            'cottage_id' => 'required|exists:cottages,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'date_start' => 'required|date|after:today',  // Fecha de inicio, debe ser después de hoy
            'date_end' => 'required|date|after:date_start',  // Fecha de fin, debe ser después de la fecha de inicio
            'total_price' => 'required|numeric|min:0',
            'state' => 'required',
            'date_reservation' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $ext_reservation = Reservation::where('cottage_id', $request->cottage_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_start', [$request->date_start, $request->date_end])
                    ->orWhereBetween('date_end', [$request->date_start, $request->date_end])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('date_start', '<=', $request->date_start)
                            ->where('date_end', '>=', $request->date_end);
                    });
            })
            ->exists();

        if ($ext_reservation) {
            return response()->json([
                'message' => 'La cabaña ya ha sido reservada en las fechas seleccionadas. Por favor, elige otras fechas.'
            ], 409); // Código de estado HTTP 409: Conflicto
        }

        // Crear una nueva reserva con los datos validados
        $reservation = Reservation::create($request->all());

        // Retornar una respuesta, puede ser un redireccionamiento o una respuesta JSON
        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'reservation' => $reservation
        ], 201);
    }
    
}
