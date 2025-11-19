@extends('layouts.app')

@section('title', 'Cadastro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 py-8">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl p-8 border border-gray-200">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl mb-4 shadow-lg">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Cadastro de Aluno</h1>
                <p class="text-gray-600 text-center mt-2">Crie sua conta gratuitamente</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        placeholder="João Silva">
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        placeholder="seu@email.com">
                </div>

                <!-- RM -->
                <div class="space-y-2">
                    <label for="rm" class="block text-sm font-medium text-gray-700">RM (Registro do Aluno)</label>
                    <input id="rm" type="text" name="rm" value="{{ old('rm') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        placeholder="2024001">
                </div>

                <!-- School Year -->
                <div class="space-y-2">
                    <label for="ano_escolar" class="block text-sm font-medium text-gray-700">Ano Escolar</label>
                    <select id="ano_escolar" name="ano_escolar" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">Selecione seu ano...</option>
                        <option value="1" {{ old('ano_escolar') == '1' ? 'selected' : '' }}>1º ano</option>
                        <option value="2" {{ old('ano_escolar') == '2' ? 'selected' : '' }}>2º ano</option>
                        <option value="3" {{ old('ano_escolar') == '3' ? 'selected' : '' }}>3º ano</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        placeholder="••••••••">
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 rounded-lg shadow-md hover:shadow-lg transition-all">
                    Cadastrar
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Já tem conta?
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">Faça login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection