@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Administrativo</h1>
        <p class="text-gray-600">Visão geral completa do sistema da biblioteca</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Livros -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Total de Livros</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_livros'] }}</p>
            <p class="text-sm text-gray-500 mt-2">
                <span class="text-green-600 font-semibold">{{ $stats['livros_disponiveis'] }}</span> disponíveis
            </p>
        </div>

        <!-- Total de Usuários -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Total de Usuários</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_usuarios'] }}</p>
            <p class="text-sm text-gray-500 mt-2">
                <span class="text-purple-600 font-semibold">{{ $stats['total_alunos'] }}</span> alunos
            </p>
        </div>

        <!-- Empréstimos Ativos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Empréstimos Ativos</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['emprestimos_ativos'] }}</p>
            <p class="text-sm text-gray-500 mt-2">
                <span class="text-green-600 font-semibold">{{ $stats['total_emprestimos'] }}</span> no total
            </p>
        </div>

        <!-- Reservas Pendentes -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Reservas Pendentes</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['reservas_pendentes'] }}</p>
            <p class="text-sm text-gray-500 mt-2">
                <span class="text-yellow-600 font-semibold">{{ $stats['total_reservas'] }}</span> no total
            </p>
        </div>
    </div>

    <!-- Alertas -->
    @if($stats['emprestimos_atrasados'] > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-8">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <h3 class="text-red-800 font-bold text-lg mb-2">⚠️ Atenção! Empréstimos Atrasados</h3>
                <p class="text-red-700 mb-3">Existem <strong>{{ $stats['emprestimos_atrasados'] }} empréstimo(s)</strong> atrasado(s) que precisam de atenção.</p>
                <a href="{{ route('admin.emprestimos.index') }}" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ver Empréstimos Atrasados
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Empréstimos Recentes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Empréstimos Recentes
                </h2>
                <a href="{{ route('admin.emprestimos.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Ver todos
                </a>
            </div>

            @if($emprestimosRecentes->count() > 0)
                <div class="space-y-3">
                    @foreach($emprestimosRecentes as $emprestimo)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-12 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded flex-shrink-0 overflow-hidden">
                                @if($emprestimo->book->cover_image)
                                    <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                         alt="{{ $emprestimo->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $emprestimo->book->title }}</p>
                                <p class="text-xs text-gray-600">{{ $emprestimo->user->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            @if($emprestimo->status === 'ativo')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">Ativo</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">{{ ucfirst($emprestimo->status) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">Nenhum empréstimo recente</p>
                </div>
            @endif
        </div>

        <!-- Reservas Pendentes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    Reservas Pendentes
                </h2>
                <a href="{{ route('admin.reservas.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Ver todas
                </a>
            </div>

            @if($reservasPendentes->count() > 0)
                <div class="space-y-3">
                    @foreach($reservasPendentes as $reserva)
                        <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                            <div class="w-12 h-16 bg-gradient-to-br from-purple-400 to-purple-500 rounded flex-shrink-0 overflow-hidden">
                                @if($reserva->book->cover_image)
                                    <img src="{{ asset('storage/' . $reserva->book->cover_image) }}" 
                                         alt="{{ $reserva->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $reserva->book->title }}</p>
                                <p class="text-xs text-gray-600">{{ $reserva->user->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Pendente</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <p class="text-gray-500">Nenhuma reserva pendente</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Ações Rápidas
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.livros.create') }}" class="flex flex-col items-center justify-center p-6 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                <svg class="w-12 h-12 text-blue-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-900">Adicionar Livro</span>
            </a>

            <a href="{{ route('admin.usuarios.create') }}" class="flex flex-col items-center justify-center p-6 bg-purple-50 hover:bg-purple-100 rounded-xl transition group">
                <svg class="w-12 h-12 text-purple-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-900">Novo Usuário</span>
            </a>

            <a href="{{ route('admin.emprestimos.create') }}" class="flex flex-col items-center justify-center p-6 bg-green-50 hover:bg-green-100 rounded-xl transition group">
                <svg class="w-12 h-12 text-green-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-900">Novo Empréstimo</span>
            </a>

            <a href="{{ route('admin.autores.create') }}" class="flex flex-col items-center justify-center p-6 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition group">
                <svg class="w-12 h-12 text-yellow-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-900">Novo Autor</span>
            </a>
        </div>
    </div>
</div>
@endsection