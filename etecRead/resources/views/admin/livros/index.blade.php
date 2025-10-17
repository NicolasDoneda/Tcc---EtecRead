@extends('layouts.app')

@section('title', 'Gerenciar Livros')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Gerenciar Livros</h1>
            <p class="text-gray-600 mt-2">Administre o catálogo da biblioteca</p>
        </div>
        <a href="{{ route('admin.livros.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            + Adicionar Livro
        </a>
    </div>

    <!-- Busca -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.livros.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Buscar por título..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                🔍 Buscar
            </button>
            @if(request('search'))
            <a href="{{ route('admin.livros.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                Limpar
            </a>
            @endif
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ISBN</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ano</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Estoque</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Disponível</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($livros as $livro)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $livro->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $livro->title }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $livro->category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $livro->isbn ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $livro->year ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $livro->total_quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $livro->available_quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.livros.edit', $livro->id) }}" 
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.livros.destroy', $livro->id) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja deletar este livro?')">
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
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        Nenhum livro encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $livros->links() }}
    </div>
</div>
@endsection