@extends('layouts.app')

@section('title', 'Autores')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Autores</h1>
            <p class="text-gray-600 mt-2">Conheça os autores do nosso acervo</p>
        </div>

        <!-- Busca -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('autores.index') }}" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome do autor..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('autores.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                        Limpar
                    </a>
                @endif
            </form>
        </div>

        <!-- Total de resultados -->
        <div class="mb-4 text-gray-600">
            Mostrando <span class="font-semibold">{{ $autores->total() }}</span> autor(es)
        </div>

        <!-- Grid de Autores -->
        @if($autores->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($autores as $autor)
                    <a href="{{ route('autores.show', $autor->id) }}"
                        class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">

                        <!-- Foto do Autor -->
                        <div class="h-48 flex items-center justify-center overflow-hidden">
                            @if($autor->photo)
                                <img src="{{ asset('storage/' . $autor->photo) }}" alt="{{ $autor->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="bg-gradient-to-br from-indigo-400 to-purple-500 w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Informações -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-1">{{ $autor->name }}</h3>

                            @if($autor->bio)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $autor->bio }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic mb-3">Sem biografia disponível</p>
                            @endif

                            <!-- Datas -->
                            <div class="space-y-1 text-xs text-gray-600 mb-3 pb-3 border-b border-gray-200">
                                @if($autor->birth_date)
                                    <p class="flex items-center">
                                        <span class="mr-2">🎂</span>
                                        {{ $autor->birth_date->format('d/m/Y') }}
                                    </p>
                                @endif
                                
                                @if($autor->death_date)
                                    <p class="flex items-center">
                                        <span class="mr-2">✝️</span>
                                        {{ $autor->death_date->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Total de livros -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Obras cadastradas:</span>
                                <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $autor->books_count }} livro(s)
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $autores->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum autor encontrado</h3>
                <p class="text-gray-500 mb-4">Tente ajustar os filtros de busca</p>
                <a href="{{ route('autores.index') }}" class="text-blue-600 hover:underline">Limpar filtros</a>
            </div>
        @endif
    </div>
@endsection