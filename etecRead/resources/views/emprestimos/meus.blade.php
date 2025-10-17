@extends('layouts.app')

@section('title', 'Meus Empréstimos')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📖 Meus Empréstimos</h1>
        <p class="text-gray-600 mt-2">Acompanhe seus livros emprestados</p>
    </div>

    <!-- Empréstimos Ativos -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                {{ $emprestimosAtivos->count() }}
            </span>
            Empréstimos Ativos
        </h2>

        @if($emprestimosAtivos->count() > 0)
            <div class="space-y-4">
                @foreach($emprestimosAtivos as $emprestimo)
                @php
                    $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                    $isOverdue = $dueDate->isPast();
                    $daysLeft = now()->diffInDays($dueDate, false);
                @endphp
                
                <div class="border {{ $isOverdue ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        
                        <!-- Informações do Livro -->
                        <div class="flex-1">
                            <div class="flex items-start">
                                <div class="bg-blue-100 rounded-lg p-4 mr-4">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $emprestimo->book->title }}</h3>
                                    
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Emprestado em: <span class="font-semibold ml-1">{{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}</span>
                                        </p>
                                        
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Prazo de devolução: <span class="font-semibold ml-1">{{ $dueDate->format('d/m/Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="text-right">
                            @if($isOverdue)
                                <div class="bg-red-100 border border-red-300 rounded-lg px-4 py-2 mb-2">
                                    <p class="text-red-800 font-bold text-lg">⚠️ ATRASADO</p>
                                    <p class="text-red-600 text-sm">{{ abs($daysLeft) }} dias de atraso</p>
                                </div>
                                <p class="text-xs text-red-600 mt-2">
                                    Por favor, devolva o livro o quanto antes
                                </p>
                            @else
                                <div class="bg-green-100 border border-green-300 rounded-lg px-4 py-2 mb-2">
                                    <p class="text-green-800 font-bold text-lg">✓ No prazo</p>
                                    <p class="text-green-600 text-sm">
                                        {{ $daysLeft == 0 ? 'Vence hoje!' : ($daysLeft == 1 ? 'Vence amanhã' : "Faltam $daysLeft dias") }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Aviso -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800 text-sm">
                    <span class="font-semibold">💡 Lembre-se:</span> Você pode ter no máximo 3 empréstimos ativos simultaneamente. 
                    Para emprestar novos livros, devolva os atuais à biblioteca.
                </p>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum empréstimo ativo</h3>
                <p class="text-gray-500 mb-4">Você não possui livros emprestados no momento</p>
                <a href="{{ route('livros.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Explorar Catálogo
                </a>
            </div>
        @endif
    </div>

    <!-- Histórico de Empréstimos -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">📚 Histórico de Empréstimos</h2>

        @if($emprestimosFinalizados->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Livro</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Empréstimo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Data Devolução</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($emprestimosFinalizados as $emprestimo)
                        @php
                            $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                            $returnDate = \Carbon\Carbon::parse($emprestimo->return_date);
                            $wasLate = $returnDate->isAfter($dueDate);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $emprestimo->book->title }}</p>
                                <p class="text-sm text-gray-500">{{ $emprestimo->book->category->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $returnDate->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($wasLate)
                                    <span class="bg-orange-100 text-orange-800 text-xs px-3 py-1 rounded-full font-semibold">
                                        Devolvido com atraso
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">
                                        Devolvido no prazo
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $emprestimosFinalizados->links() }}
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Você ainda não possui histórico de empréstimos finalizados</p>
            </div>
        @endif
    </div>
</div>
@endsection