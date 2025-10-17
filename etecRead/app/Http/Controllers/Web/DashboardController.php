<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Estatísticas do aluno
        $stats = [
            'total_livros' => Book::count(),
            'livros_disponiveis' => Book::sum('available_quantity'),
            'meus_emprestimos' => Loan::where('user_id', $user->id)->where('status', 'ativo')->count(),
            'minhas_reservas' => Reservation::where('user_id', $user->id)->where('status', 'pendente')->count(),
        ];
        
        // Meus empréstimos ativos
        $emprestimosAtivos = Loan::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->with('book')
            ->latest()
            ->take(5)
            ->get();
        
        // Livros disponíveis recentes
        $livrosDisponiveis = Book::where('available_quantity', '>', 0)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();
        
        return view('dashboard', compact('stats', 'emprestimosAtivos', 'livrosDisponiveis'));
    }
}