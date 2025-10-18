@extends('layouts.app')

@section('title', 'Gerenciar Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📌 Gerenciar Reservas</h1>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aluno</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Livro</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($reservas as $reserva)
                <tr>
                    <td class="px-6 py-4">{{ $reserva->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $reserva->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $reserva->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $reserva->book->title }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($reserva->reservation_date)->format('d/m/Y') }}</td>
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
                        @if($reserva->status === 'pendente' && $reserva->book->available_quantity > 0)
                            <form method="POST" action="{{ route('admin.reservas.confirmar', $reserva->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                    ✓ Confirmar e Notificar
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection