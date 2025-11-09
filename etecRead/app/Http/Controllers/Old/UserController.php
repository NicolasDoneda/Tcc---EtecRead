<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Adiciona URL da imagem na resposta
        $users->map(function ($user) {
            $user->photo_url = $user->photo_url;
            return $user;
        });

        return $users;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'rm' => 'nullable|string|unique:users,rm|max:50',
            'role' => 'required|in:aluno,admin',
            'ano_escolar' => 'required_if:role,aluno|nullable|in:1,2,3',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $data['password'] = Hash::make($data['password']);

        // Upload da imagem
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $data['photo'] = $path;
        }

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->photo_url = $user->photo_url;
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:6', // ✅ CORRIGIDO: nullable ao invés de sometimes|required
            'rm' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id)
            ],
            'role' => 'sometimes|required|in:aluno,admin',
            'ano_escolar' => 'nullable|in:1,2,3',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        // Só atualiza a senha se foi enviada
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Remove do array se estiver vazia
        }

        // Upload da nova imagem
        if ($request->hasFile('photo')) {
            // Deleta a imagem antiga
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $data['photo'] = $path;
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Deleta a imagem
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
    }
}