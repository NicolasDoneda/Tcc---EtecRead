<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('perfil.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        // Atualiza nome e email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Atualiza senha se fornecida
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload da nova foto
        if ($request->hasFile('photo')) {
            // Deleta a foto antiga
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $path = $request->file('photo')->store('users', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('perfil.show')->with('success', 'Perfil atualizado com sucesso!');
    }
}