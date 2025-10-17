@extends('layouts.app')

@section('title', 'Minhas Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📌 Minhas Reservas</h1>
        <p class="text-gray-600 mt-2">Acompanhe suas reservas de livros</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        
        @if($reservas->count() > 0)
            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-600 text-sm font-semibold">Pendentes</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $reservas->where('status', 'pendente')->count() }}</p>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-600 text-sm font-semibold">Confirmadas</p>
                    <p class="text-3xl font-bold text-green-800">{{ $reservas->where('status', 'confirmada')->count() }}</p>
                </div>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-600 text-sm font-semibold">Canceladas</p>
                    <p class="text-3xl font-bold text-red-800">{{ $reservas->where('status', 'cancelada')->count() }}</p>
                </div>
            </div>

            <!-- Lista de Reservas -->
            <div class="space-y-4">
                @foreach($reservas as $reserva)
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        
                        <!-- Informações do Livro -->
                        <div class="flex-1">
                            <div class="flex items-start">
                                <div class="bg-purple-100 rounded-lg p-4 mr-4">
                                    <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $reserva->book->title }}</h3>
                                    
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            Categoria: <span class="font-semibold ml-1">{{ $reserva->book->category->name }}</span>
                                        </p>
                                        
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Reservado em: <span class="font-semibold ml-1">{{ \Carbon\Carbon::parse($reserva->reservation_date)->format('d/m/Y H:i') }}</span>
                                        </p>

                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            Disponíveis: <span class="font-semibold ml-1">{{ $reserva->book->available_quantity }} unidades</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="text-right">
                            @if($reserva->status === 'pendente')
                                <span class="bg-yellow-100 text-yellow-800 text-sm px-4 py-2 rounded-full font-semibold">
                                    ⏳ Pendente
                                </span>
                                <p class="text-xs text-gray-600 mt-2">Aguardando disponibilidade</p>
                            @elseif($reserva->status === 'confirmada')
                                <span class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-full font-semibold">
                                    ✓ Confirmada
                                </span>
                                <p class="text-xs text-gray-600 mt-2">Procure a biblioteca</p>
                            @else
                                <span class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-full font-semibold">
                                    ✗ Cancelada
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Ações -->
                    @if($reserva->status === 'confirmada')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-green-800 text-sm">
                                <span class="font-semibold">🎉 Livro disponível!</span> Dirija-se à biblioteca para retirar seu livro reservado.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $reservas->links() }}
            </div>

            <!-- Informações -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800 text-sm">
                    <span class="font-semibold">💡 Como funciona:</span> Quando você reserva um livro indisponível, você entrará na fila de espera. 
                    Assim que o livro for devolvido e estiver disponível, sua reserva será confirmada e você será notificado.
                </p>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhuma reserva encontrada</h3>
                <p class="text-gray-500 mb-4">Você não possui reservas de livros no momento</p>
                <a href="{{ route('livros.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Explorar Catálogo
                </a>
            </div>
        @endif
    </div>
</div>
@endsection