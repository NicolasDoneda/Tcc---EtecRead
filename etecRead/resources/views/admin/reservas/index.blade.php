@extends('layouts.app')

@section('title', 'Gerenciar Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Gerenciar Reservas</h1>
            <p class="text-gray-600">Visualize e gerencie todas as reservas do sistema</p>
        </div>
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

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.reservas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nome do aluno ou livro..."
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Reservas Pendentes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pendentes'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Reservas Confirmadas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['confirmadas'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Reservas Canceladas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['canceladas'] ?? 0 }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Reservas -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Livro</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Aluno</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Data Reserva</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Status</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Livro -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded flex-shrink-0 overflow-hidden">
                                        @if($reservation->book->cover_image)
                                            <img src="{{ asset('storage/' . $reservation->book->cover_image) }}" 
                                                 alt="{{ $reservation->book->title }}" 
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
                                        <p class="font-bold text-gray-900 text-sm">{{ $reservation->book->title }}</p>
                                        <p class="text-xs text-gray-600">{{ $reservation->book->authors->first()->name ?? 'Sem autor' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-semibold">Disponível:</span> {{ $reservation->book->available_quantity }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Aluno -->
                            <td class="py-4 px-6">
                                <p class="font-semibold text-gray-900">{{ $reservation->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $reservation->user->email }}</p>
                                @if($reservation->user->rm)
                                    <p class="text-xs text-gray-500 mt-1">RM: {{ $reservation->user->rm }}</p>
                                @endif
                            </td>

                            <!-- Data Reserva -->
                            <td class="py-4 px-6">
                                <p class="text-sm text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($reservation->reserved_at)->diffForHumans() }}
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center">
                                @if($reservation->status === 'pendente')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Pendente
                                    </span>
                                @elseif($reservation->status === 'confirmado')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Confirmado
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Cancelado
                                    </span>
                                @endif
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    @if($reservation->status === 'pendente')
                                        <form method="POST" action="{{ route('admin.reservas.confirmar', $reservation->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-2 rounded-lg transition text-sm font-semibold"
                                                    title="Confirmar Reserva">
                                                Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($reservation->status !== 'cancelado')
                                        <form method="POST" action="{{ route('admin.reservas.cancelar', $reservation->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg transition text-sm font-semibold"
                                                    title="Cancelar Reserva">
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Nenhuma reserva encontrada</p>
                                <p class="text-gray-400 text-sm mt-2">As reservas aparecem aqui quando os alunos reservam livros</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection