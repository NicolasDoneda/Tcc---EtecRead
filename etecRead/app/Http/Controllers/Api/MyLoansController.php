<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class MyLoansController extends Controller
{
    /**
     * Empréstimos ativos do usuário logado
     */
    public function active(Request $request)
    {
        $user = $request->user();

        $loans = Loan::with(['book.category'])
            ->where('user_id', $user->id)
            ->where('status', 'ativo')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $loans->count(),
            'data' => $loans->map(function($loan) {
                $isOverdue = now()->gt($loan->due_date);
                $daysRemaining = now()->diffInDays($loan->due_date, false);

                return [
                    'id' => $loan->id,
                    'loan_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'is_overdue' => $isOverdue,
                    'days_remaining' => (int) $daysRemaining,
                    'book' => [
                        'id' => $loan->book->id,
                        'title' => $loan->book->title,
                        'cover_image' => $loan->book->cover_image ? asset('storage/' . $loan->book->cover_image) : null,
                        'category' => $loan->book->category->name,
                    ],
                ];
            })
        ], 200);
    }

    /**
     * Histórico de empréstimos do usuário logado
     */
    public function history(Request $request)
    {
        $user = $request->user();

        $loans = Loan::with(['book.category'])
            ->where('user_id', $user->id)
            ->where('status', 'finalizado')
            ->orderBy('return_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $loans->count(),
            'data' => $loans->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'loan_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'return_date' => $loan->return_date,
                    'was_late' => $loan->return_date ? now()->parse($loan->return_date)->gt($loan->due_date) : false,
                    'book' => [
                        'id' => $loan->book->id,
                        'title' => $loan->book->title,
                        'cover_image' => $loan->book->cover_image ? asset('storage/' . $loan->book->cover_image) : null,
                        'category' => $loan->book->category->name,
                    ],
                ];
            })
        ], 200);
    }

    /**
     * Resumo dos empréstimos do usuário
     */
    public function summary(Request $request)
    {
        $user = $request->user();

        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->count();

        $overdueLoans = Loan::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->where('due_date', '<', now())
            ->count();

        $totalLoans = Loan::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'active_loans' => $activeLoans,
                'overdue_loans' => $overdueLoans,
                'total_loans' => $totalLoans,
                'available_slots' => 3 - $activeLoans, // Limite de 3 empréstimos
            ]
        ], 200);
    }
}