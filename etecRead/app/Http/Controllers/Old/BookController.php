<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::with('category')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'nullable|string|unique:books,isbn',
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
        ]);

        // Validação: available_quantity não pode ser maior que total_quantity
        if ($data['available_quantity'] > $data['total_quantity']) {
            return response()->json([
                'error' => 'Quantidade disponível não pode ser maior que quantidade total'
            ], 422);
        }

        $book = Book::create($data);

        return response()->json($book->load('category'), 201);
    }

    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'isbn' => 'nullable|string|unique:books,isbn,' . $id,
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_quantity' => 'sometimes|required|integer|min:0',
            'available_quantity' => 'sometimes|required|integer|min:0',
        ]);

        // Validação: available_quantity não pode ser maior que total_quantity
        if (isset($data['available_quantity']) && isset($data['total_quantity'])) {
            if ($data['available_quantity'] > $data['total_quantity']) {
                return response()->json([
                    'error' => 'Quantidade disponível não pode ser maior que quantidade total'
                ], 422);
            }
        }

        $book->update($data);

        return response()->json($book->load('category'));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Livro deletado com sucesso'], 200);
    }
}