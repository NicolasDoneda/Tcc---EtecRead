@extends('layouts.app')

@section('title', 'Gerenciar Categorias')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📁 Gerenciar Categorias</h1>
            <p class="text-gray-600 mt-2">Organize o catálogo por categorias</p>
        </div>
        <a href="{{ route('admin.categorias.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            + Adicionar Categoria
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total de Livros</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categorias as $categoria)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $categoria->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $categoria->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $categoria->books_count }} livros
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.categorias.edit', $categoria->id) }}" 
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.categorias.destroy', $categoria->id) }}" 
                                  onsubmit="return confirm('Tem certeza? Isso pode afetar os livros desta categoria.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        Nenhuma categoria encontrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categorias->links() }}
    </div>
</div>
@endsection