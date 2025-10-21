<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::with(['user', 'book', 'reservation'])->get();
    }

    public function store(Request $request)
    {
        Log::info('=== INÍCIO STORE LOAN ===');
        Log::info('Dados recebidos:', $request->all());

        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'due_date' => 'nullable|date|after:today',
                'return_date' => 'nullable|date',
                'status' => 'nullable|in:ativo,finalizado',
                'reservation_id' => 'nullable|exists:reservations,id',
            ]);

            Log::info('passou na validação');

        } catch (ValidationException $e) {
            Log::error('erro de validação:', $e->errors());
            throw $e;
        }

        // Verifica se o usuário existe e é aluno
        $user = User::find($validated['user_id']);
        if ($user->role !== 'aluno') {
            return response()->json([
                'error' => 'Apenas alunos podem fazer empréstimos'
            ], 422);
        }

        // Verifica se o livro tem estoque disponível
        $book = Book::find($validated['book_id']);
        if (!$book->hasAvailableStock()) {
            return response()->json([
                'error' => 'Livro sem estoque disponível',
                'available_quantity' => $book->available_quantity
            ], 422);
        }

        // Verifica se o aluno já tem empréstimo ativo deste livro
        $emprestimoAtivo = Loan::where('user_id', $validated['user_id'])
            ->where('book_id', $validated['book_id'])
            ->where('status', 'ativo')
            ->exists();

        if ($emprestimoAtivo) {
            return response()->json([
                'error' => 'Você já possui um empréstimo ativo deste livro'
            ], 422);
        }

        // Verifica limite de empréstimos ativos (máximo 3)
        $totalEmprestimosAtivos = Loan::where('user_id', $validated['user_id'])
            ->where('status', 'ativo')
            ->count();

        if ($totalEmprestimosAtivos >= 3) {
            return response()->json([
                'error' => 'Você atingiu o limite de 3 empréstimos simultâneos'
            ], 422);
        }

        // Remove campos null
        $data = array_filter($validated, function ($value) {
            return $value !== null;
        });

        // Define loan_date e due_date se não fornecidos
        $data['loan_date'] = $data['loan_date'] ?? Carbon::now();
        $data['due_date'] = $data['due_date'] ?? Carbon::now()->addDays(14); // 14 dias padrão
        $data['status'] = $data['status'] ?? 'ativo';

        Log::info('Dados processados:', $data);

        try {
            // Cria o empréstimo
            $loan = Loan::create($data);

            // Diminui o estoque do livro
            $book->decreaseStock();

            Log::info('loan criado!', ['id' => $loan->id]);

            return response()->json($loan->load(['user', 'book', 'reservation']), 201);

        } catch (\Exception $e) {
            Log::error('erro ao criar loan');
            Log::error('Mensagem: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $loan = Loan::with(['user', 'book', 'reservation'])->findOrFail($id);
        return response()->json($loan);
    }

    public function update(Request $request, $id)
    {
        Log::info('=== INÍCIO UPDATE LOAN ===');

        $loan = Loan::findOrFail($id);
        Log::info('Loan encontrado:', $loan->toArray());

        try {
            $validated = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'book_id' => 'sometimes|required|exists:books,id',
                'due_date' => 'nullable|date',
                'return_date' => 'nullable|date',
                'status' => 'sometimes|in:ativo,finalizado',
                'reservation_id' => 'nullable|exists:reservations,id',
            ]);

            Log::info('passou na validação update');

        } catch (ValidationException $e) {
            Log::error('erro na validação update:', $e->errors());
            throw $e;
        }

        $data = array_filter($validated, function ($value) {
            return $value !== null;
        });

        // Se mudou o status para finalizado e estava ativo, aumenta estoque
        if (isset($data['status']) && $data['status'] === 'finalizado' && $loan->status === 'ativo') {
            $data['return_date'] = $data['return_date'] ?? Carbon::now();

            // Aumenta o estoque do livro
            $loan->book->increaseStock();

            Log::info('Empréstimo finalizado, estoque aumentado');
        }

        try {
            $loan->update($data);
            Log::info('loan atualizado', $loan->toArray());

            return response()->json($loan->load(['user', 'book', 'reservation']));

        } catch (\Exception $e) {
            Log::error('erro au atualizar', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        // Se o empréstimo está ativo, devolve o estoque
        if ($loan->status === 'ativo') {
            $loan->book->increaseStock();
        }

        $loan->delete();

        return response()->json(['message' => 'Empréstimo deletado com sucesso'], 200);
    }
}