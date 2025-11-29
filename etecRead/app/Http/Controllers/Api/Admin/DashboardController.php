<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard - Visão geral do sistema
     */
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_students' => User::where('role', 'aluno')->count(),
            'total_books' => Book::sum('total_quantity'),
            'available_books' => Book::sum('available_quantity'),
            'active_loans' => Loan::where('status', 'ativo')->count(),
            'pending_reservations' => Reservation::where('status', 'pendente')->count(),
            'overdue_loans' => Loan::where('status', 'ativo')
                ->where('due_date', '<', now())
                ->count(),
        ];

        // Livros mais emprestados
        $topBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'loans_count' => $book->loans_count,
                    'cover_image' => $book->cover_image ? asset('storage/' . $book->cover_image) : null,
                ];
            });

        // Alunos por ano escolar
        $studentsByYear = User::where('role', 'aluno')
            ->selectRaw('ano_escolar, COUNT(*) as count')
            ->groupBy('ano_escolar')
            ->orderBy('ano_escolar')
            ->get()
            ->map(function($item) {
                return [
                    'year' => $item->ano_escolar . 'º ano',
                    'count' => $item->count,
                ];
            });

        // Empréstimos recentes
        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'student_name' => $loan->user->name,
                    'book_title' => $loan->book->title,
                    'loan_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'status' => $loan->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'top_books' => $topBooks,
                'students_by_year' => $studentsByYear,
                'recent_loans' => $recentLoans,
            ]
        ], 200);
    }
}