<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Lista todos os empréstimos
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por atrasados
        if ($request->has('overdue') && $request->overdue == 'true') {
            $query->where('status', 'ativo')
                  ->where('due_date', '<', now());
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $loans = $query->get();

        return response()->json([
            'success' => true,
            'count' => $loans->count(),
            'data' => $loans->map(function($loan) {
                $isOverdue = $loan->status === 'ativo' && now()->gt($loan->due_date);

                return [
                    'id' => $loan->id,
                    'student' => [
                        'id' => $loan->user->id,
                        'name' => $loan->user->name,
                        'rm' => $loan->user->rm,
                        'ano_escolar' => $loan->user->ano_escolar,
                    ],
                    'book' => [
                        'id' => $loan->book->id,
                        'title' => $loan->book->title,
                        'cover_image' => $loan->book->cover_image ? asset('storage/' . $loan->book->cover_image) : null,
                        'category' => $loan->book->category->name,
                    ],
                    'loan_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'return_date' => $loan->return_date,
                    'status' => $loan->status,
                    'is_overdue' => $isOverdue,
                    'days_overdue' => $isOverdue ? now()->diffInDays($loan->due_date) : 0,
                ];
            })
        ], 200);
    }

    /**
     * Estatísticas de empréstimos
     */
    public function statistics()
    {
        $total = Loan::count();
        $active = Loan::where('status', 'ativo')->count();
        $finished = Loan::where('status', 'finalizado')->count();
        $overdue = Loan::where('status', 'ativo')
            ->where('due_date', '<', now())
            ->count();

        // Empréstimos por mês (últimos 6 meses)
        $loansByMonth = Loan::selectRaw('MONTH(loan_date) as month, YEAR(loan_date) as year, COUNT(*) as count')
            ->where('loan_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'month' => $item->month . '/' . $item->year,
                    'count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'finished' => $finished,
                'overdue' => $overdue,
                'by_month' => $loansByMonth,
            ]
        ], 200);
    }
}