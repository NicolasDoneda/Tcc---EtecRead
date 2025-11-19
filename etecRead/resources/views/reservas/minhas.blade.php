@extends('layouts.app')

@section('title', 'Minhas Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Minhas Reservas</h1>
        <p class="text-gray-600">Acompanhe suas reservas de livros e seja notificado quando estiverem disponíveis</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pendentes -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Reservas Pendentes</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $reservasPendentes }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Confirmadas -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Confirmadas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $reservasConfirmadas }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Canceladas -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Canceladas</p>
                    <p class="text-3xl font-bold text-red-600">{{ $reservasCanceladas }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-md mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('pendentes')" id="tab-pendentes" class="tab-button active border-b-2 border-yellow-600 text-yellow-600 px-6 py-4 font-semibold">
                    Pendentes ({{ $reservasPendentes }})
                </button>
                <button onclick="showTab('historico')" id="tab-historico" class="tab-button border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300 px-6 py-4 font-semibold">
                    Histórico
                </button>
            </nav>
        </div>

        <!-- Tab Content: Pendentes -->
        <div id="content-pendentes" class="tab-content p-6">
            @if($reservas->where('status', 'pendente')->count() > 0)
                <div class="space-y-4">
                    @foreach($reservas->where('status', 'pendente') as $reserva)
                        <div class="flex items-center gap-4 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-xl hover:shadow-md transition">
                            <!-- Book Cover -->
                            <div class="w-16 h-20 bg-gradient-to-br from-purple-400 to-purple-500 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if($reserva->book->cover_image)
                                    <img src="{{ asset('storage/' . $reserva->book->cover_image) }}" 
                                         alt="{{ $reserva->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">{{ $reserva->book->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $reserva->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Reservado em: {{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Aguardando disponibilidade
                                    </span>
                                    @if($reserva->book->available_quantity > 0)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Livro disponível agora!
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('livros.show', $reserva->book->id) }}" 
                                   class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Livro
                                </a>
                                <form method="POST" action="{{ route('reservas.cancelar', $reserva->id) }}" onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhuma reserva pendente</h3>
                    <p class="text-gray-600 mb-6">Você não possui reservas aguardando no momento</p>
                    <a href="{{ route('livros.index') }}" class="inline-flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Explorar Catálogo
                    </a>
                </div>
            @endif
        </div>

        <!-- Tab Content: Histórico -->
        <div id="content-historico" class="tab-content hidden p-6">
            @if($todasReservas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Livro</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Data Reserva</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todasReservas as $reserva)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-12 bg-gradient-to-br from-purple-400 to-purple-500 rounded flex-shrink-0 overflow-hidden">
                                                @if($reserva->book->cover_image)
                                                    <img src="{{ asset('storage/' . $reserva->book->cover_image) }}" 
                                                         alt="{{ $reserva->book->title }}" 
                                                         class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $reserva->book->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $reserva->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($reserva->status === 'pendente')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Pendente</span>
                                        @elseif($reserva->status === 'confirmado')
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Confirmada</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('livros.show', $reserva->book->id) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            Ver livro
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $todasReservas->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum histórico encontrado</h3>
                    <p class="text-gray-600">Você ainda não realizou nenhuma reserva</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function showTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-yellow-600', 'text-yellow-600');
            button.classList.add('border-transparent', 'text-gray-600');
        });
        
        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tab);
        activeButton.classList.add('active', 'border-yellow-600', 'text-yellow-600');
        activeButton.classList.remove('border-transparent', 'text-gray-600');
    }
</script>
@endsection