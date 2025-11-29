@extends('layouts.app')

@section('title', 'Gerenciar Empréstimos')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gerenciar Empréstimos</h1>
            <p class="text-gray-600 mt-2">Controle todos os empréstimos da biblioteca</p>
        </div>
        <a href="{{ route('admin.emprestimos.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            + Novo Empréstimo
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.emprestimos.index') }}" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Todos os status</option>
                <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativos</option>
                <option value="finalizado" {{ request('status') == 'finalizado' ? 'selected' : '' }}>Finalizados</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                Filtrar
            </button>
            @if(request('status'))
            <a href="{{ route('admin.emprestimos.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                Limpar
            </a>
            @endif
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aluno</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Livro</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Empréstimo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Devolução</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($emprestimos as $emprestimo)
                @php
                    $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                    $isOverdue = $emprestimo->status === 'ativo' && $dueDate->isPast();
                @endphp
                <tr class="hover:bg-gray-50 {{ $isOverdue ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $emprestimo->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $emprestimo->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $emprestimo->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $emprestimo->book->title }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm {{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                        {{ $dueDate->format('d/m/Y') }}
                        @if($isOverdue)
                            <span class="block text-xs text-red-500">
                                ({{ $dueDate->diffInDays(now()) }} dias atraso)
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($emprestimo->status === 'ativo')
                            @if($isOverdue)
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ● Atrasado
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ● Ativo
                                </span>
                            @endif
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                ✓ Finalizado
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.emprestimos.edit', $emprestimo->id) }}" 
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.emprestimos.destroy', $emprestimo->id) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja deletar este empréstimo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Nenhum empréstimo encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $emprestimos->links() }}
    </div>
</div>
@endsection