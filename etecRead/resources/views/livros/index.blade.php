@extends('layouts.app')

@section('title', 'Livros')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📚 Catálogo de Livros</h1>
        <p class="text-gray-600 mt-2">Explore nossa coleção</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('livros.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar por título</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Digite o nome do livro..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Categoria -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todas as categorias</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('category_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Disponibilidade -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-semibold">
                    🔍 Filtrar
                </button>
            </div>
        </form>

        <!-- Filtro Rápido -->
        <div class="mt-4 flex items-center">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" 
                       onchange="window.location.href='{{ route('livros.index') }}?{{ http_build_query(array_merge(request()->except('disponivel'), ['disponivel' => request('disponivel') ? '' : '1'])) }}'"
                       {{ request('disponivel') ? 'checked' : '' }}
                       class="mr-2 h-4 w-4 text-blue-600">
                <span class="text-sm text-gray-700">Mostrar apenas livros disponíveis</span>
            </label>
        </div>
    </div>

    <!-- Total de resultados -->
    <div class="mb-4 text-gray-600">
        Mostrando <span class="font-semibold">{{ $livros->total() }}</span> livro(s)
    </div>

    <!-- Grid de Livros -->
    @if($livros->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            @foreach($livros as $livro)
<a href="{{ route('livros.show', $livro->id) }}" 
   class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
    
    <!-- ✅ Imagem do Livro -->
    <div class="h-48 flex items-center justify-center overflow-hidden">
        @if($livro->cover_image)
            <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                 alt="{{ $livro->title }}" 
                 class="w-full h-full object-cover">
        @else
            <div class="bg-gradient-to-br from-blue-400 to-purple-500 w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
    </div>

    <!-- Informações -->
    <div class="p-4">
        <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">{{ $livro->title }}</h3>
        
        <div class="space-y-2 text-sm text-gray-600">
            <p class="flex items-center">
                <span class="font-semibold mr-2">📁</span>
                {{ $livro->category->name }}
            </p>
            
            @if($livro->year)
            <p class="flex items-center">
                <span class="font-semibold mr-2">📅</span>
                {{ $livro->year }}
            </p>
            @endif

            @if($livro->isbn)
            <p class="flex items-center text-xs">
                <span class="font-semibold mr-2">ISBN:</span>
                {{ $livro->isbn }}
            </p>
            @endif
        </div>

        <!-- Disponibilidade -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            @if($livro->available_quantity > 0)
                <div class="flex items-center justify-between">
                    <span class="text-green-600 font-semibold">✓ Disponível</span>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                        {{ $livro->available_quantity }} un.
                    </span>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <span class="text-red-600 font-semibold">✗ Indisponível</span>
                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                        Sem estoque
                    </span>
                </div>
            @endif
        </div>
    </div>
</a>
@endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $livros->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum livro encontrado</h3>
            <p class="text-gray-500 mb-4">Tente ajustar os filtros de busca</p>
            <a href="{{ route('livros.index') }}" class="text-blue-600 hover:underline">Limpar filtros</a>
        </div>
    @endif
</div>
@endsection