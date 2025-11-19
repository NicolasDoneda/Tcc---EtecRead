@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Olá, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                <p class="text-gray-300">Bem-vindo de volta ao sistema da biblioteca</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm px-6 py-3 rounded-lg">
                <p class="text-sm text-gray-300">Membro desde</p>
                <p class="text-lg font-bold">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Livros -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Livros</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_livros'] }}</p>
            <p class="text-gray-500 text-sm mt-1">no acervo</p>
        </div>

        <!-- Disponíveis -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Disponíveis</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['livros_disponiveis'] }}</p>
            <p class="text-gray-500 text-sm mt-1">para empréstimo</p>
        </div>

        <!-- Empréstimos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Empréstimos</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['meus_emprestimos'] }}</p>
            <p class="text-gray-500 text-sm mt-1">ativos agora</p>
        </div>

        <!-- Reservas -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Reservas</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['minhas_reservas'] }}</p>
            <p class="text-gray-500 text-sm mt-1">pendentes</p>
        </div>
    </div>

    <!-- Meus Empréstimos Ativos -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Meus Empréstimos Ativos
            </h2>
            <a href="{{ route('emprestimos.meus') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Ver todos
            </a>
        </div>

        @if($emprestimosAtivos->count() > 0)
            <div class="space-y-4">
                @foreach($emprestimosAtivos as $emprestimo)
                    @php
                        $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                        $loanDate = \Carbon\Carbon::parse($emprestimo->loan_date);
                        $isOverdue = $dueDate->isPast();
                        $totalDays = $loanDate->diffInDays($dueDate);
                        $daysLeft = round(now()->diffInDays($dueDate, false));
                        $daysPassed = $totalDays - $daysLeft;
                        $percentage = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <!-- Book Image -->
                        <div class="w-16 h-20 bg-gradient-to-br from-blue-400 to-blue-500 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($emprestimo->book->cover_image)
                                <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" alt="{{ $emprestimo->book->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 mb-1">{{ $emprestimo->book->title }}</h3>
                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Devolução: {{ $dueDate->format('d/m/Y') }}
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $isOverdue ? 'bg-red-500' : 'bg-pink-500' }}" style="width: {{ min(100, $percentage) }}%"></div>
                            </div>

                            <p class="text-sm {{ $isOverdue ? 'text-red-600' : 'text-gray-600' }} mt-1 font-medium">
                                {{ $isOverdue ? 'Vencido' : ($percentage >= 70 ? 'Vencendo' : 'No prazo') }}
                            </p>
                        </div>

                        <!-- Circular Progress -->
                        <div class="flex-shrink-0">
                            <div class="relative w-16 h-16">
                                <svg class="transform -rotate-90 w-16 h-16">
                                    <circle cx="32" cy="32" r="28" stroke="#e5e7eb" stroke-width="4" fill="none" />
                                    <circle cx="32" cy="32" r="28" 
                                            stroke="{{ $isOverdue ? '#ef4444' : '#ec4899' }}" 
                                            stroke-width="4" 
                                            fill="none"
                                            stroke-dasharray="{{ 2 * 3.14159 * 28 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - min(100, $percentage) / 100) }}"
                                            stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-bold {{ $isOverdue ? 'text-red-600' : 'text-pink-600' }}">
                                        {{ min(100, $percentage) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-gray-500 font-medium">Você não tem empréstimos ativos</p>
            </div>
        @endif
    </div>

    <!-- Livros Disponíveis -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Livros Disponíveis
            </h2>
            <a href="{{ route('livros.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Ver catálogo
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($livrosDisponiveis as $livro)
                <a href="{{ route('livros.show', $livro->id) }}" class="group">
                    <div class="relative overflow-hidden rounded-lg bg-gray-100 h-48 mb-2">
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold z-10">
                            Disponível
                        </span>
                        @if($livro->cover_image)
                            <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                                 alt="{{ $livro->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 group-hover:text-blue-600 transition">{{ $livro->title }}</h3>
                    <p class="text-xs text-gray-500">{{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $livro->category->name }}</span>
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('livros.index') }}" class="bg-gray-900 hover:bg-gray-800 text-white rounded-xl p-6 flex items-center justify-between group transition shadow-lg">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="font-semibold">Explorar Catálogo</span>
            </div>
        </a>

        <a href="{{ route('emprestimos.meus') }}" class="bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-xl p-6 flex items-center justify-between group transition shadow-md">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-gray-900">Ver Empréstimos</span>
            </div>
        </a>

        <a href="{{ route('reservas.minhas') }}" class="bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-xl p-6 flex items-center justify-between group transition shadow-md">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <span class="font-semibold text-gray-900">Minhas Reservas</span>
            </div>
        </a>
    </div>
</div>
@endsection