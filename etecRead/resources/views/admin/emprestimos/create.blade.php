@extends('layouts.app')

@section('title', 'Criar Empréstimo')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="mb-8">
        <a href="{{ route('admin.emprestimos.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Voltar para lista
        </a>
        <h1 class="text-3xl font-bold text-gray-800">📖 Criar Novo Empréstimo</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.emprestimos.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Aluno -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Aluno *</label>
                    <select name="user_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            required>
                        <option value="">Selecione um aluno...</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Livro -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Livro *</label>
                    <select name="book_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            required>
                        <option value="">Selecione um livro...</option>
                        @foreach($livros as $livro)
                            <option value="{{ $livro->id }}" {{ old('book_id') == $livro->id ? 'selected' : '' }}>
                                {{ $livro->title }} ({{ $livro->available_quantity }} disponíveis)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Apenas livros com estoque disponível</p>
                </div>

                <!-- Data de Devolução -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Data de Devolução Prevista *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}" 
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Prazo padrão: 14 dias a partir de hoje</p>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Criar Empréstimo
                </button>
                <a href="{{ route('admin.emprestimos.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection