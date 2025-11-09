<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Relatório mensal
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:' . date('Y'),
        ]);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Empréstimos do mês
        $loans = Loan::whereBetween('loan_date', [$startDate, $endDate])->get();
        $loansCount = $loans->count();
        $activeLoansCount = $loans->where('status', 'ativo')->count();
        $finishedLoansCount = $loans->where('status', 'finalizado')->count();
        $overdueLoansCount = Loan::where('status', 'ativo')
            ->where('due_date', '<', now())
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->count();

        // Reservas do mês
        $reservations = Reservation::whereBetween('reserved_at', [$startDate, $endDate])->get();
        $reservationsCount = $reservations->count();
        $pendingReservationsCount = $reservations->where('status', 'pendente')->count();

        // Novos usuários do mês
        $newUsersCount = User::where('role', 'aluno')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Livros mais emprestados no mês
        $topBooks = Loan::with('book')
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->selectRaw('book_id, COUNT(*) as count')
            ->groupBy('book_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get()
            ->map(function($item) {
                return [
                    'book_title' => $item->book->title,
                    'loans_count' => $item->count,
                ];
            });

        // Alunos mais ativos
        $topStudents = Loan::with('user')
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get()
            ->map(function($item) {
                return [
                    'student_name' => $item->user->name,
                    'rm' => $item->user->rm,
                    'loans_count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'period' => [
                'month' => $month,
                'year' => $year,
                'month_name' => Carbon::create($year, $month)->locale('pt_BR')->monthName,
            ],
            'data' => [
                'loans' => [
                    'total' => $loansCount,
                    'active' => $activeLoansCount,
                    'finished' => $finishedLoansCount,
                    'overdue' => $overdueLoansCount,
                ],
                'reservations' => [
                    'total' => $reservationsCount,
                    'pending' => $pendingReservationsCount,
                ],
                'new_students' => $newUsersCount,
                'top_books' => $topBooks,
                'top_students' => $topStudents,
            ]
        ], 200);
    }

    /**
     * Relatório geral do sistema
     */
    public function overview()
    {
        // Totais gerais
        $totalStudents = User::where('role', 'aluno')->count();
        $totalBooks = Book::sum('total_quantity');
        $availableBooks = Book::sum('available_quantity');
        $totalLoans = Loan::count();
        $totalReservations = Reservation::count();

        // Empréstimos por status
        $loansByStatus = [
            'active' => Loan::where('status', 'ativo')->count(),
            'finished' => Loan::where('status', 'finalizado')->count(),
            'overdue' => Loan::where('status', 'ativo')->where('due_date', '<', now())->count(),
        ];

        // Últimos 6 meses de empréstimos
        $loansTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Loan::whereYear('loan_date', $date->year)
                ->whereMonth('loan_date', $date->month)
                ->count();
            
            $loansTrend[] = [
                'month' => $date->format('M/Y'),
                'count' => $count,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_students' => $totalStudents,
                    'total_books' => $totalBooks,
                    'available_books' => $availableBooks,
                    'total_loans' => $totalLoans,
                    'total_reservations' => $totalReservations,
                ],
                'loans_by_status' => $loansByStatus,
                'loans_trend' => $loansTrend,
            ]
        ], 200);
    }

    /**
     * Gerar PDF do relatório mensal
     * Nota: Precisa instalar dompdf ou similar
     * composer require barryvdh/laravel-dompdf
     */
    public function downloadPDF(Request $request)
    {
        // Por enquanto retorna URL para download
        // Você pode implementar geração de PDF real depois
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        return response()->json([
            'success' => true,
            'message' => 'Funcionalidade de PDF será implementada',
            'data' => [
                'month' => $month,
                'year' => $year,
                'note' => 'Instale barryvdh/laravel-dompdf para gerar PDFs'
            ]
        ], 200);
    }
}