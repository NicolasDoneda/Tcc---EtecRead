@extends('layouts.app')

@section('title', 'Editar Empréstimo')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('admin.emprestimos.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
                ← Voltar para lista
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Editar Empréstimo #{{ $emprestimo->id }}</h1>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informações do Empréstimo -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-blue-800 mb-3">Informações do Empréstimo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-blue-600 font-semibold">Aluno:</p>
                        <p class="text-blue-900">{{ $emprestimo->user->name }}</p>
                        <p class="text-blue-700 text-xs">{{ $emprestimo->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-blue-600 font-semibold">Livro:</p>
                        <p class="text-blue-900">{{ $emprestimo->book->title }}</p>
                    </div>
                    <div>
                        <p class="text-blue-600 font-semibold">Data do Empréstimo:</p>
                        <p class="text-blue-900">{{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-600 font-semibold">Status Atual:</p>
                        @if($emprestimo->status === 'ativo')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">●
                                Ativo</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">✓
                                Finalizado</span>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.emprestimos.update', $emprestimo->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Data de Devolução Prevista -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Data de Devolução Prevista</label>
                        <input type="date" name="due_date"
                            value="{{ old('due_date', \Carbon\Carbon::parse($emprestimo->due_date)->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Data de Devolução Real -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Data de Devolução Real</label>
                        <input type="date" name="return_date"
                            value="{{ old('return_date', $emprestimo->return_date ? \Carbon\Carbon::parse($emprestimo->return_date)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Deixe vazio se ainda não foi devolvido</p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value="ativo" {{ old('status', $emprestimo->status) == 'ativo' ? 'selected' : '' }}>Ativo
                            </option>
                            <option value="finalizado" {{ old('status', $emprestimo->status) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @if($emprestimo->status === 'ativo')
                            <p class="text-xs text-yellow-600 mt-2 bg-yellow-50 border border-yellow-200 rounded p-2">
                                Ao finalizar o empréstimo, o estoque do livro será automaticamente aumentado.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Atualizar Empréstimo
                    </button>
                    <a href="{{ route('admin.emprestimos.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection