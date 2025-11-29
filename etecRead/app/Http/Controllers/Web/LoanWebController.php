<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;

class LoanWebController extends Controller
{
    public function myLoans()
    {
        $user = auth()->user();
        
        $emprestimosAtivos = Loan::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->with('book')
            ->latest()
            ->get();
        
        $emprestimosFinalizados = Loan::where('user_id', $user->id)
            ->where('status', 'finalizado')
            ->with('book')
            ->latest()
            ->paginate(10);
        
        return view('emprestimos.meus', compact('emprestimosAtivos', 'emprestimosFinalizados'));
    }
    
    public function myReservations()
    {
        $user = auth()->user();
        
        $reservas = Reservation::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->paginate(15);
        
        return view('reservas.minhas', compact('reservas'));
    }
}