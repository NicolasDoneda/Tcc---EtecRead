@extends('layouts.app')

@section('title', 'Adicionar Categoria')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="mb-8">
        <a href="{{ route('admin.categorias.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Voltar para lista
        </a>
        <h1 class="text-3xl font-bold text-gray-800">📁 Adicionar Nova Categoria</h1>
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

        <form method="POST" action="{{ route('admin.categorias.store') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nome da Categoria *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       placeholder="Ex: Ficção, Romance, Suspense..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       required>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Salvar Categoria
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection