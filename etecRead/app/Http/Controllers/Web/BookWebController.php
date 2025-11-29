<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtro por disponibilidade
        if ($request->filled('disponivel')) {
            $query->where('available_quantity', '>', 0);
        }
        
        // Busca por título
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $livros = $query->paginate(12);
        $categorias = Category::all();
        
        return view('livros.index', compact('livros', 'categorias'));
    }
    
    public function show($id)
    {
        $livro = Book::with('category')->findOrFail($id);
        
        return view('livros.show', compact('livro'));
    }
}