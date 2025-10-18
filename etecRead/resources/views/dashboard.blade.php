@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Olá, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-600 mt-2">Bem-vindo ao sistema de biblioteca</p>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <!-- Total de Livros -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total de Livros</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_livros'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Livros Disponíveis -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Disponíveis</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['livros_disponiveis'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Meus Empréstimos -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Meus Empréstimos</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['meus_emprestimos'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Minhas Reservas -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Minhas Reservas</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['minhas_reservas'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de 2 Colunas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Meus Empréstimos Ativos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">📖 Meus Empréstimos Ativos</h2>
                <a href="{{ route('emprestimos.meus') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
            </div>

            @if($emprestimosAtivos->count() > 0)
                <div class="space-y-4">
                    @foreach($emprestimosAtivos as $emprestimo)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $emprestimo->book->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Emprestado em: {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                                        $isOverdue = $dueDate->isPast();
                                        $daysLeft = now()->diffInDays($dueDate, false);
                                    @endphp

                                    @if($isOverdue)
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                            Atrasado
                                        </span>
                                        <p class="text-xs text-red-600 mt-1">
                                            {{ abs($daysLeft) }} dias de atraso
                                        </p>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                            No prazo
                                        </span>
                                        <p class="text-xs text-gray-600 mt-1">
                                            Devolução: {{ $dueDate->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <p>Você não tem empréstimos ativos</p>
                </div>
            @endif
        </div>

        <!-- Livros Disponíveis Recentes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">📚 Livros Disponíveis</h2>
                <a href="{{ route('livros.index') }}" class="text-blue-600 hover:underline text-sm">Ver todos</a>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                @foreach($livrosDisponiveis as $livro)
                    <a href="{{ route('livros.show', $livro->id) }}"
                        class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md hover:border-blue-300 transition">

                        <!-- Imagem do livro -->
                        <div class="h-32 flex items-center justify-center overflow-hidden">
                            @if($livro->cover_image)
                                <img src="{{ asset('storage/' . $livro->cover_image) }}" alt="{{ $livro->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="bg-blue-100 w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-3">
                            <h3 class="font-semibold text-sm text-gray-800 truncate">{{ $livro->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $livro->category->name }}</p>
                            <p class="text-xs text-green-600 font-semibold mt-1">
                                {{ $livro->available_quantity }} disponíveis
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection