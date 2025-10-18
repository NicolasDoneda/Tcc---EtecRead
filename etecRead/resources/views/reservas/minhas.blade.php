@extends('layouts.app')

@section('title', 'Gerenciar Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📌 Gerenciar Reservas</h1>
            <p class="text-gray-600 mt-2">Controle as reservas de livros dos alunos</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.reservas.index') }}" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Todos os status</option>
                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                <option value="confirmada" {{ request('status') == 'confirmada' ? 'selected' : '' }}>Confirmadas</option>
                <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                🔍 Filtrar
            </button>
            @if(request('status'))
            <a href="{{ route('admin.reservas.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                Limpar
            </a>
            @endif
        </form>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aluno</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Livro</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Reserva</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Disponível</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reservas as $reserva)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $reserva->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $reserva->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $reserva->user->email }}</p>
                        @if($reserva->user->rm)
                            <p class="text-xs text-gray-500">RM: {{ $reserva->user->rm }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $reserva->book->title }}</p>
                        <p class="text-xs text-gray-500">{{ $reserva->book->category->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($reserva->reservation_date)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($reserva->book->available_quantity > 0)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ✓ {{ $reserva->book->available_quantity }} un.
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ✗ Sem estoque
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($reserva->status === 'pendente')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ⏳ Pendente
                            </span>
                        @elseif($reserva->status === 'confirmada')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ✓ Confirmada
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ✗ Cancelada
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            @if($reserva->status === 'pendente' && $reserva->book->available_quantity > 0)
                                <form method="POST" action="{{ route('admin.reservas.confirmar', $reserva->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm font-semibold"
                                            onclick="return confirm('Confirmar reserva e enviar email para o aluno?')">
                                        ✓ Confirmar
                                    </button>
                                </form>
                            @endif
                            
                            @if($reserva->status === 'pendente')
                                <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm font-semibold"
                                            onclick="return confirm('Cancelar esta reserva?')">
                                        ✗ Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <p>Nenhuma reserva encontrada</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $reservas->links() }}
    </div>
</div>
@endsection