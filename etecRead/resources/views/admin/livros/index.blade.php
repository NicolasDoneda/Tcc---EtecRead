@extends('layouts.app')

@section('title', 'Gerenciar Livros')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Gerenciar Livros</h1>
            <p class="text-gray-600 mt-2">Administre o catálogo da biblioteca</p>
        </div>
        <a href="{{ route('admin.livros.create') }}"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            + Adicionar Livro
        </a>
    </div>

    <!-- Busca -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.livros.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                🔍 Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.livros.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                    Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @forelse($livros as $livro)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                
                <!-- Imagem do Livro -->
                <div class="h-48 flex items-center justify-center overflow-hidden relative">
                    @if($livro->cover_image)
                        <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                             alt="{{ $livro->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="bg-gradient-to-br from-blue-400 to-purple-500 w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge ID -->
                    <div class="absolute top-2 left-2 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-xs font-bold">
                        #{{ $livro->id }}
                    </div>
                </div>

                <!-- Informações -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">{{ $livro->title }}</h3>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p class="flex items-center">
                            <span class="font-semibold mr-2">📁</span>
                            {{ $livro->category->name }}
                        </p>

                        @if($livro->isbn)
                            <p class="flex items-center text-xs">
                                <span class="font-semibold mr-2">ISBN:</span>
                                {{ $livro->isbn }}
                            </p>
                        @endif
                        
                        @if($livro->year)
                            <p class="flex items-center text-xs">
                                <span class="font-semibold mr-2">📅</span>
                                {{ $livro->year }}
                            </p>
                        @endif
                    </div>

                    <!-- Estoque -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-500">Estoque Total:</span>
                            <span class="text-sm font-bold text-gray-800">{{ $livro->total_quantity }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Disponível:</span>
                            <span class="text-sm font-bold text-green-600">{{ $livro->available_quantity }}</span>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.livros.edit', $livro->id) }}" 
                           class="flex-1 bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 text-sm text-center font-semibold">
                            ✏️ Editar
                        </a>
                        <form method="POST" action="{{ route('admin.livros.destroy', $livro->id) }}" 
                              onsubmit="return confirm('Tem certeza que deseja deletar este livro?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 text-sm font-semibold">
                                🗑️ Deletar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-lg">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum livro encontrado</h3>
                <p class="text-gray-500">Adicione livros para começar</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $livros->links() }}
    </div>
</div>
@endsection