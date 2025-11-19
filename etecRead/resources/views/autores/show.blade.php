@extends('layouts.app')

@section('title', $autor->name)

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="mb-8">
        <a href="javascript:history.back()" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda - Card do Autor -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <!-- Foto do Autor -->
                <div class="flex flex-col items-center mb-6">
                    <div class="w-40 h-40 rounded-full overflow-hidden mb-4 border-4 border-indigo-500 shadow-lg">
                        @if($autor->photo)
                            <img src="{{ asset('storage/' . $autor->photo) }}" 
                                 alt="{{ $autor->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 text-center">{{ $autor->name }}</h1>
                </div>

                <!-- Informações do Autor -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    @if($autor->birth_date)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">🎂 Nascimento</p>
                        <p class="text-gray-800 font-semibold">
                            {{ $autor->birth_date->format('d/m/Y') }}
                            @if(!$autor->death_date)
                                <span class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($autor->birth_date)->age }} anos)</span>
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($autor->death_date)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">✝️ Falecimento</p>
                        <p class="text-gray-800 font-semibold">
                            {{ $autor->death_date->format('d/m/Y') }}
                            <span class="text-xs text-gray-500">
                                ({{ \Carbon\Carbon::parse($autor->birth_date)->diffInYears($autor->death_date) }} anos)
                            </span>
                        </p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">📚 Total de Obras</p>
                        <p class="text-gray-800 font-semibold">{{ $autor->books()->count() }} livro(s)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita - Biografia e Livros -->
        <div class="lg:col-span-2">
            
            <!-- Biografia -->
            @if($autor->bio)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Biografia
                </h2>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $autor->bio }}
                </div>
            </div>
            @endif

            <!-- Livros do Autor -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        Obras deste Autor
                    </span>
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $autor->books()->count() }}
                    </span>
                </h2>

                @if($autor->books()->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($autor->books as $livro)
                            <a href="{{ route('livros.show', $livro->id) }}" 
                               class="flex bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition group">
                                <!-- Capa do Livro -->
                                <div class="flex-shrink-0 w-16 h-24 rounded overflow-hidden mr-4">
                                    @if($livro->cover_image)
                                        <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                                             alt="{{ $livro->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Informações do Livro -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition line-clamp-2 mb-1">
                                        {{ $livro->title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $livro->category->name }}</p>
                                    
                                    @if($livro->available_quantity > 0)
                                        <span class="inline-flex items-center text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Disponível
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-semibold">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Indisponível
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <p class="text-gray-500">Nenhum livro cadastrado ainda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection