<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::with('authors', 'category')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn' => 'nullable|string|unique:books,isbn',
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer',
            'total_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'authors' => 'array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book = Book::create($data);

        if (!empty($data['authors'])) {
            $book->authors()->sync($data['authors']);
        }

        return response()->json($book->load('authors', 'category'), 201);
    }

    public function show($id)
    {
        $book = Book::with('authors', 'category')->findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer',
            'total_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'authors' => 'array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book->update($data);

        if (isset($data['authors'])) {
            $book->authors()->sync($data['authors']);
        }

        return response()->json($book->load('authors', 'category'));
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, 204);
    }
}
