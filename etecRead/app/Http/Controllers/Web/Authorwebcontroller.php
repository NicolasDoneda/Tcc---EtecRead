<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $autores = $query->paginate(12);

        return view('autores.index', compact('autores'));
    }

    public function show($id)
    {
        $autor = Author::with(['books.category'])->findOrFail($id);
        
        return view('autores.show', compact('autor'));
    }
}