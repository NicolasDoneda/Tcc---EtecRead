@extends('layouts.app')

@section('title', $livro->title)

@section('content')
<div class="max-w-7xl mx-auto px-4">
    
    <!-- Botão Voltar -->
    <div class="mb-6">
        <a href="{{ route('livros.index') }}" class="text-blue-600 hover:underline flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para o catálogo
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Coluna Esquerda - Imagem -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg shadow-xl p-12 flex items-center justify-center">
                <svg class="w-48 h-48 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>

            <!-- Card de Disponibilidade -->
            <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">📊 Disponibilidade</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-semibold">{{ $livro->total_quantity }} unidades</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Disponíveis:</span>
                        <span class="font-semibold text-green-600">{{ $livro->available_quantity }} unidades</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Emprestados:</span>
                        <span class="font-semibold text-orange-600">{{ $livro->total_quantity - $livro->available_quantity }} unidades</span>
                    </div>
                </div>

                <!-- Barra de Progresso -->
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-3">
                        @php
                            $percentage = $livro->total_quantity > 0 ? ($livro->available_quantity / $livro->total_quantity) * 100 : 0;
                        @endphp
                        <div class="bg-green-500 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-6">
                    @if($livro->available_quantity > 0)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <p class="text-green-800 font-semibold text-lg">✓ Disponível para empréstimo</p>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <p class="text-red-800 font-semibold text-lg">✗ Sem estoque no momento</p>
                            <p class="text-red-600 text-sm mt-2">Você pode fazer uma reserva</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna Direita - Informações -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                
                <!-- Título -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $livro->title }}</h1>

                <!-- Informações Básicas -->
                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500">Categoria</p>
                        <p class="font-semibold text-gray-800">📁 {{ $livro->category->name }}</p>
                    </div>
                    
                    @if($livro->year)
                    <div>
                        <p class="text-sm text-gray-500">Ano de Publicação</p>
                        <p class="font-semibold text-gray-800">📅 {{ $livro->year }}</p>
                    </div>
                    @endif
                    
                    @if($livro->isbn)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">ISBN</p>
                        <p class="font-semibold text-gray-800">{{ $livro->isbn }}</p>
                    </div>
                    @endif
                </div>

                <!-- Descrição (se tiver no futuro) -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">📖 Sobre o livro</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Este livro faz parte do acervo da biblioteca escolar e está disponível para empréstimo aos alunos.
                        O prazo padrão de empréstimo é de 14 dias, podendo ser renovado caso não haja reservas pendentes.
                    </p>
                </div>

                <!-- Informações Adicionais -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-bold text-blue-800 mb-3">ℹ️ Informações Importantes</h3>
                    <ul class="space-y-2 text-blue-700 text-sm">
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Prazo de empréstimo: 14 dias</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Limite máximo: 3 empréstimos simultâneos por aluno</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Procure a bibliotecária para realizar o empréstimo</span>
                        </li>
                        @if($livro->available_quantity == 0)
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Você pode fazer uma reserva para ser avisado quando o livro estiver disponível</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection