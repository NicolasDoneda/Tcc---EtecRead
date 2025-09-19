<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rm' => 'required|string|unique:users,rm',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'in:aluno,admin',
            'status' => 'in:ativo,inativo',
            'photo' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'rm' => 'required|string|unique:users,rm,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required|string',
            'password' => 'nullable|string|min:6',
            'role' => 'in:aluno,admin',
            'status' => 'in:ativo,inativo',
            'photo' => 'nullable|string',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
