@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-500 to-blue-600 py-8">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-800">📚</h1>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">Cadastro de Aluno</h2>
            <p class="text-gray-600 mt-1">Crie sua conta gratuitamente</p>
        </div>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nome Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="João Silva"
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="seu@email.com"
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">RM (Registro do Aluno)</label>
                <input type="text" name="rm" value="{{ old('rm') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="2024001">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Ano Escolar</label>
                <select name="ano_escolar" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                        required>
                    <option value="">Selecione seu ano...</option>
                    <option value="1" {{ old('ano_escolar') == '1' ? 'selected' : '' }}>1º ano</option>
                    <option value="2" {{ old('ano_escolar') == '2' ? 'selected' : '' }}>2º ano</option>
                    <option value="3" {{ old('ano_escolar') == '3' ? 'selected' : '' }}>3º ano</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Senha</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="••••••••"
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Confirmar Senha</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="••••••••"
                       required>
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold transition">
                Cadastrar
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Já tem conta? 
                <a href="{{ route('login') }}" class="text-green-600 hover:underline font-semibold">Faça login</a>
            </p>
        </div>
    </div>
</div>
@endsection