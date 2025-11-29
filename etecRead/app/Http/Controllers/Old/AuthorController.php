<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        return Author::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
        ]);

        $author = Author::create($data);

        return response()->json($author, 201);
    }

    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor não encontrado'], 404);
        }

        return response()->json($author);
    }

    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor não encontrado'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
        ]);

        $author->update($data);

        return response()->json([
            'message' => 'Autor atualizado com sucesso',
            'author' => $author
        ]);
    }

    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor não encontrado'], 404);
        }

        $author->delete();

        return response()->json(['message' => 'Autor deletado com sucesso'], 200);
    }
}
