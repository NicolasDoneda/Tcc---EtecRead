<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        // Retorna todas as reservas com os relacionamentos
        return Reservation::with(['user', 'book'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'status' => 'in:pendente,confirmado,cancelado',
        ]);

        $reservation = Reservation::create($data);

        return response()->json($reservation->load(['user', 'book']), 201);
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'book'])->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reserva não encontrada'], 404);
        }

        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reserva não encontrada'], 404);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'status' => 'in:pendente,confirmado,cancelado',
        ]);

        $reservation->update($data);

        return response()->json([
            'message' => 'Reserva atualizada com sucesso',
            'reservation' => $reservation->load(['user', 'book'])
        ]);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reserva não encontrada'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reserva deletada com sucesso'], 200);
    }
}
