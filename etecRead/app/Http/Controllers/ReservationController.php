<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
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

    public function show(Reservation $reservation)
    {
        return $reservation->load(['user', 'book']);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'status' => 'in:pendente,confirmado,cancelado',
        ]);

        $reservation->update($data);
        return response()->json($reservation->load(['user', 'book']));
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }
}
