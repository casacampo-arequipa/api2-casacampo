<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cottage;
use App\Models\Package;
use App\Models\Reservation;
use App\Models\User;
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
            $reservations = Reservation::with('package', 'cottages')->get();
        }

        return response()->json(['reservations' => $reservations]);
    }
    public function helps()
    {
        $users = User::select('id', 'name', 'lastname')->get();
        $packages = Package::select('id', 'name', 'guarantee', 'cleaning', 'price_monday_to_thursday', 'price_friday_to_sunday')->get();
        $cottages = Cottage::select('id', 'name_cottage')->get();

        return response()->json([
            'users' => $users,
            'packages' => $packages,
            'cottages' => $cottages,
        ], 200);
    }
    public function store(Request $request)
    {
        // Validar los datos que vienen del formulario o API
        $validator = Validator::make(request()->all(), [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'cottage_ids' => 'required|array',
            'cottage_ids.*' => 'exists:cottages,id',
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

        try {
            // Iterar sobre cada cabaña para verificar disponibilidad
            foreach ($request->cottage_ids as $cottage_id) {
                $conflict = Reservation::whereHas('cottages', function ($query) use ($cottage_id) {
                    $query->where('cottage_id', $cottage_id);
                })
                    ->where('date_start', '<=', $request->date_end)
                    ->where('date_end', '>=', $request->date_start)
                    ->exists();

                if ($conflict) {
                    // Rollback y respuesta de conflicto
                    return response()->json([
                        'message' => "La cabaña con ID {$cottage_id} ya está reservada en las fechas seleccionadas. Por favor, elige otras fechas."
                    ], 409); // Código de estado HTTP 409: Conflicto
                }
            }

            // Es recomendable especificar los campos en lugar de usar $request->all()
            $reservation = Reservation::create($request->all());

            // Asociar las cabañas a la reservación
            $reservation->cottages()->attach($request->cottage_ids);

            // Retornar una respuesta exitosa con las cabañas asociadas
            return response()->json([
                'message' => 'Reserva creada exitosamente',
                'reservation' => $reservation->load('cottages')
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la reservación.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $validator = Validator::make(request()->all(), [
                'user_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:packages,id',
                'cottage_ids' => 'required|array',
                'cottage_ids.*' => 'exists:cottages,id',
                'discount_id' => 'nullable|exists:discounts,id',
                'promotion_id' => 'nullable|exists:promotions,id',
                'date_start' => 'required|date|after:today',  // Fecha de inicio, debe ser después de hoy
                'date_end' => 'required|date|after:date_start',  // Fecha de fin, debe ser después de la fecha de inicio
                'total_price' => 'required|numeric|min:0',
                'state' => 'required',
                'date_reservation' => 'required|date'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400); // Código de estado HTTP 400: Solicitud Incorrecta
            }

            if ($request->has('cottage_ids')) {
                foreach ($request->cottage_ids as $cottage_id) {
                    $conflict = Reservation::whereHas('cottages', function ($query) use ($cottage_id) {
                        $query->where('cottage_id', $cottage_id);
                    })
                        ->where('id', '!=', $id) // Excluir la reservación actual
                        ->where('date_start', '<=', $request->date_end)
                        ->where('date_end', '>=', $request->date_start)
                        ->exists();

                    if ($conflict) {
                        // Rollback y respuesta de conflicto

                        return response()->json([
                            'message' => "La cabaña con ID {$cottage_id} ya está reservada en las fechas seleccionadas. Por favor, elige otras fechas."
                        ], 409); // Código de estado HTTP 409: Conflicto
                    }
                }
            }
            $reservation->update($request->all());

            if ($request->has('cottage_ids')) {
                // Primero, eliminar las asociaciones existentes que no están en el nuevo array
                $reservation->cottages()->sync($request->cottage_ids);
            }

            return response()->json([
                'message' => 'Reserva actualizada exitosamente',
                'reservation' => $reservation->load('cottages')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar la reservación.'], 500);
        }
    }
}
