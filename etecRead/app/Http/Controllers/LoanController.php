<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::with(['user', 'book', 'reservation'])->get();
    }

    public function store(Request $request)
    {
        Log::info('=== INÍCIO STORE ===');
        Log::info('Dados recebidos:', $request->all());

        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'due_date' => 'nullable|date',
                'return_date' => 'nullable|date',
                'status' => 'nullable|in:ativo,finalizado',
                'reservation_id' => 'nullable|exists:reservations,id',
            ]);
            
            Log::info('✅ PASSOU NA VALIDAÇÃO!');
            Log::info('Dados validados:', $validated);
            
        } catch (ValidationException $e) {
            Log::error('❌ ERRO DE VALIDAÇÃO:', $e->errors());
            throw $e;
        }

        // Remove campos null
        $data = array_filter($validated, function($value) {
            return $value !== null;
        });

        Log::info('Dados após filtrar nulls:', $data);

        try {
            Log::info('Tentando criar Loan...');
            $loan = Loan::create($data);
            Log::info('✅ LOAN CRIADO!', ['id' => $loan->id, 'loan' => $loan->toArray()]);
            
            return response()->json($loan->load(['user', 'book', 'reservation']), 201);
            
        } catch (\Exception $e) {
            Log::error('❌ ERRO AO CRIAR LOAN');
            Log::error('Mensagem: ' . $e->getMessage());
            Log::error('Arquivo: ' . $e->getFile() . ':' . $e->getLine());
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
        Log::info('=== INÍCIO UPDATE ===');
        Log::info('Loan ID:', ['id' => $id]);
        
        $loan = Loan::findOrFail($id);
        Log::info('Loan encontrado:', $loan->toArray());

        Log::info('Dados recebidos:', $request->all());

        try {
            $validated = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'book_id' => 'sometimes|required|exists:books,id',
                'due_date' => 'nullable|date',
                'return_date' => 'nullable|date',
                'status' => 'sometimes|in:ativo,finalizado',
                'reservation_id' => 'nullable|exists:reservations,id',
            ]);
            
            Log::info('✅ PASSOU NA VALIDAÇÃO UPDATE!');
            
        } catch (ValidationException $e) {
            Log::error('❌ ERRO DE VALIDAÇÃO UPDATE:', $e->errors());
            throw $e;
        }

        $data = array_filter($validated, function($value) {
            return $value !== null;
        });

        try {
            $loan->update($data);
            Log::info('✅ LOAN ATUALIZADO!', $loan->toArray());
            
            return response()->json($loan->load(['user', 'book', 'reservation']));
            
        } catch (\Exception $e) {
            Log::error('❌ ERRO AO ATUALIZAR:', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Empréstimo deletado com sucesso'], 200);
    }
}