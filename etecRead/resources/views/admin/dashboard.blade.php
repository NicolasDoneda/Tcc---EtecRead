@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📊 Dashboard Administrativo</h1>
        <p class="text-gray-600 mt-2">Visão geral do sistema de biblioteca</p>
    </div>

    <!-- Widgets de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Total de Alunos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total de Alunos</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $stats['total_alunos'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total de Livros -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total de Livros</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $stats['total_livros'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Livros Disponíveis -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Livros Disponíveis</p>
                    <p class="text-4xl font-bold text-purple-600 mt-2">{{ $stats['livros_disponiveis'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Empréstimos Ativos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Empréstimos Ativos</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">{{ $stats['emprestimos_ativos'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reservas Pendentes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Reservas Pendentes</p>
                    <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $stats['reservas_pendentes'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total de Empréstimos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total de Empréstimos</p>
                    <p class="text-4xl font-bold text-indigo-600 mt-2">{{ $stats['total_emprestimos'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de 2 colunas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Alunos por Ano Escolar -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-6 flex items-center">
                🎓 Alunos por Ano Escolar
            </h2>
            <div class="space-y-4">
                @forelse($alunosPorAno as $ano)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold text-lg">{{ $ano->ano_escolar }}º</span>
                        </div>
                        <span class="text-gray-700 font-semibold">{{ $ano->ano_escolar }}º ano</span>
                    </div>
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full font-bold">
                        {{ $ano->total }} alunos
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Nenhum aluno cadastrado</p>
                @endforelse
            </div>
        </div>

        <!-- Livros Mais Emprestados -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-6 flex items-center">
                🏆 Top 5 Livros Mais Emprestados
            </h2>
            <div class="space-y-4">
                @forelse($livrosMaisEmprestados as $index => $livro)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center flex-1">
                        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full w-10 h-10 flex items-center justify-center mr-4 text-white font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 font-semibold truncate">{{ $livro->title }}</p>
                            <p class="text-xs text-gray-500">{{ $livro->category->name }}</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold ml-4">
                        {{ $livro->loans_count }} vezes
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Nenhum empréstimo registrado</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Empréstimos Atrasados -->
    @if($emprestimosAtrasados->count() > 0)
    <div class="bg-red-50 border-2 border-red-300 rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-red-800 mb-6 flex items-center">
            ⚠️ Empréstimos Atrasados ({{ $emprestimosAtrasados->count() }})
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase">Aluno</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase">Livro</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase">Data Prevista</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-800 uppercase">Dias de Atraso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100">
                    @foreach($emprestimosAtrasados as $emprestimo)
                    <tr class="hover:bg-red-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $emprestimo->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $emprestimo->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $emprestimo->book->title }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($emprestimo->due_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-red-200 text-red-900 px-3 py-1 rounded-full text-sm font-bold">
                                {{ \Carbon\Carbon::parse($emprestimo->due_date)->diffInDays(now()) }} dias
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Empréstimos Recentes -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            📅 Empréstimos Recentes
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aluno</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Livro</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Empréstimo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Devolução</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($emprestimosRecentes as $emprestimo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $emprestimo->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $emprestimo->user->rm ?? 'Sem RM' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $emprestimo->book->title }}</p>
                            <p class="text-sm text-gray-500">{{ $emprestimo->book->category->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($emprestimo->due_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($emprestimo->status === 'ativo')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ● Ativo
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✓ Finalizado
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Nenhum empréstimo registrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ações Administrativas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Promover Alunos -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <h3 class="text-xl font-bold mb-3">🎓 Promover Alunos</h3>
            <p class="text-blue-100 mb-4 text-sm">
                Promove todos os alunos para o próximo ano escolar (1º→2º, 2º→3º)
            </p>
            <form method="POST" action="{{ route('admin.promover') }}" onsubmit="return confirm('Tem certeza que deseja promover todos os alunos?')">
                @csrf
                <button type="submit" class="w-full bg-white text-blue-600 py-3 rounded-lg hover:bg-blue-50 transition font-semibold">
                    Executar Promoção
                </button>
            </form>
        </div>

        <!-- Deletar Formandos -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <h3 class="text-xl font-bold mb-3">🗑️ Deletar Formandos</h3>
            <p class="text-red-100 mb-4 text-sm">
                Remove todos os alunos do 3º ano do sistema (executar após formatura)
            </p>
            <form method="POST" action="{{ route('admin.deletar-formandos') }}" onsubmit="return confirm('⚠️ ATENÇÃO: Esta ação irá deletar permanentemente todos os alunos do 3º ano! Deseja continuar?')">
                @csrf
                <button type="submit" class="w-full bg-white text-red-600 py-3 rounded-lg hover:bg-red-50 transition font-semibold">
                    Deletar Formandos
                </button>
            </form>
        </div>
    </div>
</div>
@endsection