<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redireciona baseado na role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Bem-vindo, Admin!');
            }
            
            return redirect()->route('dashboard')->with('success', 'Bem-vindo!');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rm' => 'nullable|string|unique:users|max:50',
            'ano_escolar' => 'required|in:1,2,3',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'rm' => $validated['rm'],
            'password' => Hash::make($validated['password']),
            'role' => 'aluno',
            'ano_escolar' => $validated['ano_escolar'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Cadastro realizado com sucesso!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }
}