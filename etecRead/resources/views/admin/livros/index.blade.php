@extends('layouts.app')

@section('title', 'Gerenciar Livros')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Gerenciar Livros</h1>
            <p class="text-gray-600">Adicione, edite ou remova livros do acervo</p>
        </div>
        <a href="{{ route('admin.livros.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Adicionar Livro
        </a>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.livros.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Título, autor ou ISBN..."
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Categoria -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                <select name="category_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botão Filtrar -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Livros -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Livro</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Categoria</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">ISBN</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Quantidade</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Disponível</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Livro -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded flex-shrink-0 overflow-hidden">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                                 alt="{{ $book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $book->title }}</p>
                                        <p class="text-sm text-gray-600">{{ $book->authors->first()->name ?? 'Sem autor' }}</p>
                                        @if($book->year)
                                            <p class="text-xs text-gray-500">{{ $book->year }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Categoria -->
                            <td class="py-4 px-6">
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $book->category->name }}
                                </span>
                            </td>

                            <!-- ISBN -->
                            <td class="py-4 px-6">
                                <p class="text-sm text-gray-900 font-mono">{{ $book->isbn ?? '-' }}</p>
                            </td>

                            <!-- Quantidade Total -->
                            <td class="py-4 px-6 text-center">
                                <span class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full">
                                    {{ $book->total_quantity }}
                                </span>
                            </td>

                            <!-- Quantidade Disponível -->
                            <td class="py-4 px-6 text-center">
                                <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                                    {{ $book->available_quantity }}
                                </span>
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.livros.edit', $book->id) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.livros.destroy', $book->id) }}" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este livro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition"
                                                title="Excluir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Nenhum livro encontrado</p>
                                <p class="text-gray-400 text-sm mt-2">Adicione um novo livro ao acervo</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection