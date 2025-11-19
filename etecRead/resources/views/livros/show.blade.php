@extends('layouts.app')

@section('title', $livro->title)

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Back Button -->
    <a href="{{ route('livros.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6 transition group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span class="font-medium">Voltar para o Catálogo</span>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Book Cover Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-8">
                <!-- Book Cover -->
                <div class="relative h-96 bg-gradient-to-br from-gray-100 to-gray-200">
                    @if($livro->cover_image)
                        <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                             alt="{{ $livro->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    @if($livro->available_quantity > 0)
                        <span class="absolute top-4 right-4 bg-green-500 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-lg flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Disponível
                        </span>
                    @else
                        <span class="absolute top-4 right-4 bg-red-500 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-lg flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Indisponível
                        </span>
                    @endif
                </div>

                <!-- Book Info Card -->
                <div class="p-6 space-y-6">
                    <!-- Author -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Autor</h3>
                        @if($livro->authors->first())
                            @php
                                $autor = $livro->authors->first();
                            @endphp
                            <div class="flex items-center space-x-3">
                                @if($autor->photo)
                                    <img src="{{ asset('storage/' . $autor->photo) }}" 
                                         alt="{{ $autor->name }}"
                                         class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($autor->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ $autor->name }}</p>
                                    @if($autor->bio)
                                        <p class="text-xs text-gray-500 line-clamp-2">{{ Str::limit($autor->bio, 60) }}</p>
                                    @else
                                        <p class="text-xs text-gray-400 italic">Sem biografia disponível</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Autor não informado</p>
                        @endif
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Category -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            Categoria
                        </h3>
                        <span class="inline-block bg-purple-100 text-purple-800 text-sm font-semibold px-3 py-1.5 rounded-lg">
                            {{ $livro->category->name }}
                        </span>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Publication Year -->
                    @if($livro->year)
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Publicação
                            </h3>
                            <p class="font-bold text-gray-900">{{ $livro->year }}</p>
                        </div>
                        <div class="border-t border-gray-200"></div>
                    @endif

                    <!-- ISBN -->
                    @if($livro->isbn)
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                ISBN
                            </h3>
                            <p class="font-mono text-sm text-gray-900">{{ $livro->isbn }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Book Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Basic Info -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $livro->title }}</h1>
                <p class="text-gray-600 text-lg mb-4">
                    <svg class="w-5 h-5 inline mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    por {{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}
                </p>
            </div>

            <!-- Availability Stats -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Disponibilidade
                </h2>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <!-- Total -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $livro->total_quantity }}</p>
                        <p class="text-sm text-blue-800 font-medium mt-1">Total</p>
                    </div>

                    <!-- Disponíveis -->
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $livro->available_quantity }}</p>
                        <p class="text-sm text-green-800 font-medium mt-1">Disponíveis</p>
                    </div>

                    <!-- Emprestados -->
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold text-red-600">{{ $livro->total_quantity - $livro->available_quantity }}</p>
                        <p class="text-sm text-red-800 font-medium mt-1">Emprestados</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                        <span>Disponibilidade</span>
                        <span>{{ $livro->total_quantity > 0 ? round(($livro->available_quantity / $livro->total_quantity) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $livro->total_quantity > 0 ? ($livro->available_quantity / $livro->total_quantity) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <!-- Availability Alert -->
                @if(auth()->user()->role === 'aluno')
                    @if($livro->available_quantity > 0)
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                <div>
                                    <p class="font-semibold text-green-800 text-sm">Este livro está <strong>disponível</strong> para empréstimo!</p>
                                    <p class="text-sm text-green-700 mt-1">Dirija-se à biblioteca para realizar o empréstimo.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $jaReservou = $livro->reservations()
                                ->where('user_id', auth()->id())
                                ->where('status', 'pendente')
                                ->exists();
                        @endphp

                        @if(!$jaReservou)
                            <form method="POST" action="{{ route('reservas.store') }}">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $livro->id }}">
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="font-semibold text-yellow-800 text-sm">Todas as cópias estão emprestadas no momento</p>
                                                <p class="text-sm text-yellow-700 mt-1">Você pode reservar este livro e será notificado quando estiver disponível.</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="ml-4 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-6 py-2 rounded-lg transition shadow-md">
                                            Reservar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-blue-800 text-sm">Você já reservou este livro</p>
                                        <p class="text-sm text-blue-700 mt-1">Aguarde a disponibilidade. Você será notificado.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <!-- Admin View -->
                    @if($livro->available_quantity > 0)
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                <div>
                                    <p class="font-semibold text-green-800 text-sm">Este livro está <strong>disponível</strong> para empréstimo!</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-semibold text-red-800 text-sm">Todas as cópias estão emprestadas no momento</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- About the Book -->
            @if($livro->description)
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        Sobre o Livro
                    </h2>
                    <p class="text-gray-700 leading-relaxed">{{ $livro->description }}</p>
                </div>
            @endif

            <!-- Important Information -->
            @if($livro->notes)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6">
                    <h3 class="flex items-center text-lg font-bold text-yellow-900 mb-3">
                        <svg class="w-6 h-6 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Informações Importantes
                    </h3>
                    <p class="text-yellow-800">{{ $livro->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection