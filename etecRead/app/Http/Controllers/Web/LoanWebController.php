<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;

class LoanWebController extends Controller
{
    public function myLoans()
{
    $emprestimosAtivos = Loan::where('user_id', auth()->id())
        ->where('status', 'ativo')
        ->with(['book.authors', 'book.category'])
        ->orderBy('due_date', 'asc')
        ->get();

    $todosEmprestimos = Loan::where('user_id', auth()->id())
        ->with(['book.authors', 'book.category'])
        ->orderBy('loan_date', 'desc')
        ->paginate(10);

    $emprestimosConcluidos = Loan::where('user_id', auth()->id())
        ->where('status', 'concluido')
        ->count();

    $emprestimosAtrasados = Loan::where('user_id', auth()->id())
        ->where('status', 'ativo')
        ->where('due_date', '<', now())
        ->count();

    return view('emprestimos.meus', compact(
        'emprestimosAtivos',
        'todosEmprestimos',
        'emprestimosConcluidos',
        'emprestimosAtrasados'
    ));
}
    
   public function myReservations()
{
    $reservas = Reservation::where('user_id', auth()->id())
        ->with(['book.authors', 'book.category'])
        ->orderBy('reserved_at', 'desc')
        ->get();

    $todasReservas = Reservation::where('user_id', auth()->id())
        ->with(['book.authors', 'book.category'])
        ->orderBy('reserved_at', 'desc')
        ->paginate(10);

    $reservasPendentes = $reservas->where('status', 'pendente')->count();
    $reservasConfirmadas = $reservas->where('status', 'confirmado')->count(); // ⬅️ MUDOU
    $reservasCanceladas = $reservas->where('status', 'cancelado')->count(); // ⬅️ MUDOU

    return view('reservas.minhas', compact(
        'reservas',
        'todasReservas',
        'reservasPendentes',
        'reservasConfirmadas',
        'reservasCanceladas'
    ));
}
}