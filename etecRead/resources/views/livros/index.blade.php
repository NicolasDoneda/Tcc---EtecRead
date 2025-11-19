@extends('layouts.app')

@section('title', 'Catálogo de Livros')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Catálogo de Livros</h1>
        <p class="text-gray-600">Explore nossa coleção completa de {{ $livros->total() }} livros disponíveis</p>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('livros.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por título ou autor..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('category') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Books Grid -->
    @if($livros->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8">
            @foreach($livros as $livro)
                <a href="{{ route('livros.show', $livro->id) }}" class="group">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2">
                        <!-- Book Cover -->
                        <div class="relative h-64 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if($livro->cover_image)
                                <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                                     alt="{{ $livro->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            @if($livro->available_quantity > 0)
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">
                                    Disponível
                                </span>
                            @else
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">
                                    Emprestado
                                </span>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 text-sm line-clamp-2 mb-2 group-hover:text-red-600 transition">
                                {{ $livro->title }}
                            </h3>
                            <p class="text-xs text-gray-600 mb-2">
                                {{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    {{ $livro->category->name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $livro->available_quantity }}/{{ $livro->total_quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $livros->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Nenhum livro encontrado</h3>
            <p class="text-gray-600 mb-6">Tente ajustar seus filtros de busca</p>
            <a href="{{ route('livros.index') }}" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                Limpar Filtros
            </a>
        </div>
    @endif
</div>
@endsection