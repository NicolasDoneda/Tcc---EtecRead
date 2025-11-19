@extends('layouts.app')

@section('title', 'Novo Empréstimo')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.emprestimos.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Novo Empréstimo</h1>
                <p class="text-gray-600 mt-1">Registre um novo empréstimo de livro</p>
            </div>
        </div>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-red-800 font-medium mb-2">Erro ao criar empréstimo:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.emprestimos.store') }}">
        @csrf

        <div class="bg-white rounded-xl shadow-md p-8 space-y-6">
            
            <!-- Preview Icon -->
            <div class="flex justify-center">
                <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6"></div>

            <!-- Aluno -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Aluno *</label>
                <select name="user_id" id="user-select" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" 
                        required>
                    <option value="">Selecione o aluno...</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }} - {{ $usuario->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Livro -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Livro *</label>
                <select name="book_id" id="book-select" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" 
                        required>
                    <option value="">Selecione o livro...</option>
                    @foreach($livros as $livro)
                        <option value="{{ $livro->id }}" {{ old('book_id') == $livro->id ? 'selected' : '' }}>
                            {{ $livro->title }} 
                            @if($livro->authors->first())
                                - {{ $livro->authors->first()->name }}
                            @endif
                            ({{ $livro->available_quantity }} disponível)
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Apenas livros com estoque disponível</p>
            </div>

            <!-- Data de Devolução -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Data de Devolução *</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" 
                       min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" 
                       required>
                <p class="text-sm text-gray-500 mt-1">A data deve ser posterior a hoje</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-blue-800 font-medium text-sm">Informações sobre o empréstimo:</p>
                        <ul class="text-blue-700 text-sm mt-2 space-y-1">
                            <li>• A data de empréstimo será registrada automaticamente</li>
                            <li>• O status será definido como "Ativo"</li>
                            <li>• O estoque do livro será decrementado automaticamente</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Criar Empréstimo
                </button>
                <a href="{{ route('admin.emprestimos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Choices.js para os selects
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user-select');
        const bookSelect = document.getElementById('book-select');
        
        new Choices(userSelect, {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar aluno...',
            noResultsText: 'Nenhum aluno encontrado',
            itemSelectText: 'Clique para selecionar',
            shouldSort: false,
        });
        
        new Choices(bookSelect, {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar livro...',
            noResultsText: 'Nenhum livro encontrado',
            itemSelectText: 'Clique para selecionar',
            shouldSort: false,
        });
    });
</script>
@endsection