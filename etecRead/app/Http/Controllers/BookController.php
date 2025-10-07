<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::with(['authors', 'category'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn' => 'nullable|string|unique:books,isbn',
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer',
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'authors' => 'array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book = Book::create($data);

        if (!empty($data['authors'])) {
            $book->authors()->sync($data['authors']);
        }

        // Recarrega relações antes de retornar
        return response()->json($book->load(['authors', 'category']), 201);
    }

    public function show($id)
    {
        $book = Book::with(['authors', 'category'])->find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $data = $request->validate([
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer',
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'authors' => 'array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book->update($data);

        if (isset($data['authors'])) {
            $book->authors()->sync($data['authors']);
        }

        // Recarrega as relações após atualizar
        $book->load(['authors', 'category']);

        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Livro deletado com sucesso'], 200);
    }
}
