@extends('layouts.app')

@section('title', 'Adicionar Livro')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="mb-8">
        <a href="{{ route('admin.livros.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Voltar para lista
        </a>
        <h1 class="text-3xl font-bold text-gray-800">📚 Adicionar Novo Livro</h1>
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

        <form method="POST" action="{{ route('admin.livros.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Título -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Título *</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           required>
                </div>

                <!-- Categoria -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Categoria *</label>
                    <select name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            required>
                        <option value="">Selecione...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('category_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ISBN -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Ano -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Ano de Publicação</label>
                    <input type="number" name="year" value="{{ old('year') }}" 
                           min="1000" max="{{ date('Y') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Quantidade Total -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Quantidade Total *</label>
                    <input type="number" name="total_quantity" value="{{ old('total_quantity', 1) }}" 
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           required>
                </div>

                <!-- Quantidade Disponível -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Quantidade Disponível *</label>
                    <input type="number" name="available_quantity" value="{{ old('available_quantity', 1) }}" 
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Deve ser menor ou igual à quantidade total</p>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Salvar Livro
                </button>
                <a href="{{ route('admin.livros.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection