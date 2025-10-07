<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::with(['user', 'book', 'reservation'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'status' => 'in:ativo,finalizado',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $loan = Loan::create($data);

        return response()->json($loan->load(['user', 'book', 'reservation']), 201);
    }

    public function show($id)
    {
        $loan = Loan::with(['user', 'book', 'reservation'])->findOrFail($id);
        return response()->json($loan);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'status' => 'in:ativo,finalizado',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $loan->update($data);

        return response()->json($loan->load(['user', 'book', 'reservation']));
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Empréstimo deletado com sucesso'], 200);
    }
}
