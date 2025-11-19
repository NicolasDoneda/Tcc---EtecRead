@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Gerenciar Usuários</h1>
            <p class="text-gray-600">Adicione, edite ou remova usuários do sistema</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Adicionar Usuário
        </a>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nome, email ou RM..."
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition">
            </div>

            <!-- Tipo de Conta -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Conta</label>
                <select name="role" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition">
                    <option value="">Todos</option>
                    <option value="aluno" {{ request('role') == 'aluno' ? 'selected' : '' }}>Alunos</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                </select>
            </div>

            <!-- Botão Filtrar -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Usuários -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Usuário</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">RM</th>
                        <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase">Ano Escolar</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Tipo</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Empréstimos</th>
                        <th class="text-center py-4 px-6 font-bold text-gray-700 text-sm uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Usuário -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-700 rounded-full flex-shrink-0 overflow-hidden">
                                        @if($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- RM -->
                            <td class="py-4 px-6">
                                <p class="text-sm text-gray-900 font-mono">{{ $user->rm ?? '-' }}</p>
                            </td>

                            <!-- Ano Escolar -->
                            <td class="py-4 px-6">
                                @if($user->ano_escolar)
                                    <span class="text-sm text-gray-900 font-semibold">{{ $user->ano_escolar }}º ano</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Tipo -->
                            <td class="py-4 px-6 text-center">
                                @if($user->role === 'admin')
                                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Administrador
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Aluno
                                    </span>
                                @endif
                            </td>

                            <!-- Empréstimos -->
                            <td class="py-4 px-6 text-center">
                                <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                                    {{ $user->loans()->where('status', 'ativo')->count() }}
                                </span>
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.usuarios.edit', $user->id) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $user->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition"
                                                    title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Nenhum usuário encontrado</p>
                                <p class="text-gray-400 text-sm mt-2">Adicione um novo usuário ao sistema</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection