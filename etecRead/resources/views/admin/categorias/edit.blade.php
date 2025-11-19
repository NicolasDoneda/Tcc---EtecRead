@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="max-w-3xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.categorias.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Editar Categoria</h1>
                <p class="text-gray-600 mt-1">{{ $categoria->name }}</p>
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
                    <p class="text-red-800 font-medium mb-2">Erro ao atualizar categoria:</p>
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
    <form method="POST" action="{{ route('admin.categorias.update', $categoria->id) }}">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-md p-8 space-y-6">
            
            <!-- Preview Icon -->
            <div class="flex justify-center">
                <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6"></div>

            <!-- Nome -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Nome da Categoria *</label>
                <input type="text" name="name" value="{{ old('name', $categoria->name) }}" 
                       placeholder="Ex: Ficção, Romance, Tecnologia..."
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                       required>
            </div>

            <!-- Descrição -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Descrição</label>
                <textarea name="description" rows="4" 
                          placeholder="Descreva sobre que tipo de livros pertencem a essa categoria..."
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('description', $categoria->description) }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Informações adicionais sobre a categoria</p>
            </div>

            <!-- Info sobre livros -->
            @if($categoria->books->count() > 0)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-blue-800 font-medium">Esta categoria possui {{ $categoria->books->count() }} livro(s) cadastrado(s).</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botões -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection