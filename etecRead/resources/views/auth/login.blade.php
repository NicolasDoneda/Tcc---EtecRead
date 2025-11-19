@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl p-8 border border-gray-200">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl mb-4 shadow-lg">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">EtecRead</h1>
                <p class="text-gray-600 text-center mt-2">Sistema de Gerenciamento da Biblioteca</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded mb-6">
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                        placeholder="seu.email@etec.sp.gov.br">
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                        placeholder="••••••••">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                        class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                        Lembrar-me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-lg shadow-md hover:shadow-lg transition-all">
                    Entrar
                </button>
            </form>

            <!-- Info -->
            <p class="text-center text-gray-600 mt-6 text-sm">
                Não tem acesso? Entre em contato com a administração da biblioteca.
            </p>
        </div>
    </div>
</div>
@endsection