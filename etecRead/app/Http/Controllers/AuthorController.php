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
        $author = Author::findOrFail($id);
        return response()->json($author);
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
        ]);

        $author->update($data);
        return response()->json($author);
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json(null, 204);
    }
}
