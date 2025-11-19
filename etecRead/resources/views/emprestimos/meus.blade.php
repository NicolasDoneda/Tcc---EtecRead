@extends('layouts.app')

@section('title', 'Meus Empréstimos')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Meus Empréstimos</h1>
        <p class="text-gray-600">Acompanhe seus livros emprestados e histórico completo</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Ativos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Empréstimos Ativos</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $emprestimosAtivos->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Concluídos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Devolvidos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $emprestimosConcluidos }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Atrasados -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Atrasados</p>
                    <p class="text-3xl font-bold text-red-600">{{ $emprestimosAtrasados }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-md mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('ativos')" id="tab-ativos" class="tab-button active border-b-2 border-blue-600 text-blue-600 px-6 py-4 font-semibold">
                    Ativos ({{ $emprestimosAtivos->count() }})
                </button>
                <button onclick="showTab('historico')" id="tab-historico" class="tab-button border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300 px-6 py-4 font-semibold">
                    Histórico
                </button>
            </nav>
        </div>

        <!-- Tab Content: Ativos -->
        <div id="content-ativos" class="tab-content p-6">
            @if($emprestimosAtivos->count() > 0)
                <div class="space-y-4">
                    @foreach($emprestimosAtivos as $emprestimo)
                        @php
                            $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                            $loanDate = \Carbon\Carbon::parse($emprestimo->loan_date);
                            $isOverdue = $dueDate->isPast();
                            $totalDays = $loanDate->diffInDays($dueDate);
                            $daysLeft = round(now()->diffInDays($dueDate, false));
                            $daysPassed = $totalDays - $daysLeft;
                            $percentage = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                        @endphp

                        <div class="flex items-center gap-4 p-4 {{ $isOverdue ? 'bg-red-50 border-2 border-red-200' : 'bg-gray-50 border-2 border-gray-200' }} rounded-xl hover:shadow-md transition">
                            <!-- Book Cover -->
                            <div class="w-16 h-20 bg-gradient-to-br from-blue-400 to-blue-500 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if($emprestimo->book->cover_image)
                                    <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                         alt="{{ $emprestimo->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">{{ $emprestimo->book->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $emprestimo->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Empréstimo: {{ $loanDate->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Devolução: {{ $dueDate->format('d/m/Y') }}
                                        @if($isOverdue)
                                            <span class="ml-2 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">ATRASADO</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $isOverdue ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                             style="width: {{ min(100, $percentage) }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium {{ $isOverdue ? 'text-red-600' : ($percentage >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                        @if($isOverdue)
                                            Atrasado há {{ abs($daysLeft) }} dia(s)
                                        @elseif($daysLeft == 0)
                                            Vence hoje!
                                        @else
                                            {{ $daysLeft }} dia(s) restante(s)
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Circular Progress -->
                            <div class="flex-shrink-0">
                                <div class="relative w-20 h-20">
                                    <svg class="transform -rotate-90 w-20 h-20">
                                        <circle cx="40" cy="40" r="32" stroke="#e5e7eb" stroke-width="6" fill="none" />
                                        <circle cx="40" cy="40" r="32" 
                                                stroke="{{ $isOverdue ? '#ef4444' : ($percentage >= 70 ? '#eab308' : '#22c55e') }}" 
                                                stroke-width="6" 
                                                fill="none"
                                                stroke-dasharray="{{ 2 * 3.14159 * 32 }}"
                                                stroke-dashoffset="{{ 2 * 3.14159 * 32 * (1 - min(100, $percentage) / 100) }}"
                                                stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold {{ $isOverdue ? 'text-red-600' : ($percentage >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ min(100, $percentage) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum empréstimo ativo</h3>
                    <p class="text-gray-600 mb-6">Você não possui livros emprestados no momento</p>
                    <a href="{{ route('livros.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
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
            @if($todosEmprestimos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Livro</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Data Empréstimo</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Data Devolução</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todosEmprestimos as $emprestimo)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded flex-shrink-0 overflow-hidden">
                                                @if($emprestimo->book->cover_image)
                                                    <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                                         alt="{{ $emprestimo->book->title }}" 
                                                         class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $emprestimo->book->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $emprestimo->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($emprestimo->due_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($emprestimo->status === 'ativo')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Ativo</span>
                                        @elseif($emprestimo->status === 'concluido')
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Devolvido</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Atrasado</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $todosEmprestimos->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum histórico encontrado</h3>
                    <p class="text-gray-600">Você ainda não realizou nenhum empréstimo</p>
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
            button.classList.remove('active', 'border-blue-600', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-600');
        });
        
        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tab);
        activeButton.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeButton.classList.remove('border-transparent', 'text-gray-600');
    }
</script>
@endsection