<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationWebController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Verifica se o usuário é o mesmo logado
        if ($data['user_id'] != auth()->id()) {
            return back()->withErrors(['error' => 'Você só pode fazer reservas para você mesmo.']);
        }

        // Verifica se o usuário é aluno
        $user = User::find($data['user_id']);
        if ($user->role !== 'aluno') {
            return back()->withErrors(['error' => 'Apenas alunos podem fazer reservas.']);
        }

        // Verifica se já tem reserva ativa deste livro
        $reservaAtiva = Reservation::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->where('status', 'pendente')
            ->exists();

        if ($reservaAtiva) {
            return back()->withErrors(['error' => 'Você já possui uma reserva pendente deste livro.']);
        }

        // Verifica se o livro tem estoque (se tiver, não pode reservar)
        $book = Book::find($data['book_id']);
        if ($book->hasAvailableStock()) {
            return back()->withErrors(['error' => 'Livro disponível em estoque. Procure a biblioteca para fazer o empréstimo.']);
        }

        $data['reservation_date'] = Carbon::now();
        $data['status'] = 'pendente';

        Reservation::create($data);

        return back()->with('success', 'Reserva realizada com sucesso! Você será avisado quando o livro estiver disponível.');
    }
}