<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('books')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::with('books')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'books' => 'array',          // agora aceita um array de livros
            'books.*' => 'integer|exists:books,id' // valida cada id de livro
        ]);

        // Atualiza os dados básicos
        $category->update($data);

        // Se vieram livros, atualiza a relação
        if (isset($data['books'])) {
            // Atualiza a chave estrangeira category_id nos livros
            \App\Models\Book::whereIn('id', $data['books'])->update(['category_id' => $category->id]);
        }

        return response()->json(Category::with('books')->find($id));
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Categoria deletada com sucesso'], 200);
    }
}
