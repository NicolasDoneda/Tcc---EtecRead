<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'reservation_date' => 'nullable|date',
            'status' => 'nullable|in:pendente,confirmada,cancelada',
        ]);

        // Verifica se o usuário é aluno
        $user = User::find($data['user_id']);
        if ($user->role !== 'aluno') {
            return response()->json([
                'error' => 'Apenas alunos podem fazer reservas'
            ], 422);
        }

        // Verifica se já tem reserva ativa deste livro
        $reservaAtiva = Reservation::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->where('status', 'pendente')
            ->exists();

        if ($reservaAtiva) {
            return response()->json([
                'error' => 'Você já possui uma reserva pendente deste livro'
            ], 422);
        }

        // Verifica se o livro tem estoque (se tiver, não precisa reservar)
        $book = Book::find($data['book_id']);
        if ($book->hasAvailableStock()) {
            return response()->json([
                'error' => 'Livro disponível em estoque. Faça um empréstimo direto.',
                'available_quantity' => $book->available_quantity
            ], 422);
        }

        $data['reservation_date'] = $data['reservation_date'] ?? Carbon::now();
        $data['status'] = $data['status'] ?? 'pendente';

        $reservation = Reservation::create($data);

        return response()->json($reservation->load(['user', 'book']), 201);
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'book'])->findOrFail($id);
        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'book_id' => 'sometimes|required|exists:books,id',
            'reservation_date' => 'nullable|date',
            'status' => 'sometimes|in:pendente,confirmada,cancelada',
        ]);

        $reservation->update($data);

        return response()->json($reservation->load(['user', 'book']));
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(['message' => 'Reserva deletada com sucesso'], 200);
    }
}