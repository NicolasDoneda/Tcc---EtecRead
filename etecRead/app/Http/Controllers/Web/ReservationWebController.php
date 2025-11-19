<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Http\Request;

class ReservationWebController extends Controller
{
    public function store(Request $request)
    {
        // Verifica se é admin
        if (auth()->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Administradores não podem fazer reservas!');
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Verifica se o livro tem estoque
        if ($book->available_quantity > 0) {
            return back()->with('error', 'Este livro está disponível para empréstimo!');
        }

        // Verifica se o usuário já tem uma reserva ativa para este livro
        $existingReservation = Reservation::where('user_id', auth()->id())
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', ['pendente', 'confirmado'])
            ->first();

        if ($existingReservation) {
            return back()->with('error', 'Você já possui uma reserva ativa para este livro!');
        }

        // Cria a reserva
        Reservation::create([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'],
            'reserved_at' => now(),
            'status' => 'pendente',
        ]);

        return back()->with('success', 'Reserva realizada com sucesso! Você será notificado quando o livro estiver disponível.');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Verifica se a reserva pertence ao usuário logado
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('reservas.minhas')->with('error', 'Você não tem permissão para cancelar esta reserva!');
        }

        // Verifica se a reserva está em status que pode ser cancelada
        if ($reservation->status === 'cancelado') {
            return redirect()->route('reservas.minhas')->with('error', 'Esta reserva já está cancelada!');
        }

        $reservation->status = 'cancelado';
        $reservation->save();

        return redirect()->route('reservas.minhas')->with('success', 'Reserva cancelada com sucesso!');
    }
}