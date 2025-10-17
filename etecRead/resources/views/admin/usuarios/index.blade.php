@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">👥 Gerenciar Usuários</h1>
            <p class="text-gray-600 mt-2">Administre alunos e administradores</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            + Adicionar Usuário
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Busca -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nome ou email..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Filtro por Role -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Usuário</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos</option>
                    <option value="aluno" {{ request('role') == 'aluno' ? 'selected' : '' }}>Alunos</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-semibold">
                    🔍 Filtrar
                </button>
                @if(request('search') || request('role'))
                <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition font-semibold">
                    Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">RM</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ano Escolar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($usuarios as $usuario)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $usuario->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $usuario->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $usuario->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $usuario->rm ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($usuario->role === 'admin')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                👑 Admin
                            </span>
                        @else
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                🎓 Aluno
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $usuario->ano_escolar ? $usuario->ano_escolar . 'º ano' : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" 
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Editar
                            </a>
                            @if($usuario->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja deletar este usuário?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Deletar
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Nenhum usuário encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection