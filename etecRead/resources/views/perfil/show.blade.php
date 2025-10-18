@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">👤 Meu Perfil</h1>
        <p class="text-gray-600 mt-2">Visualize e edite suas informações pessoais</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda - Card de Informações -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <!-- Foto de Perfil -->
                <div class="flex flex-col items-center mb-6">
                    <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-blue-500">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                <span class="text-white font-bold text-4xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 text-center">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>
                </div>

                <!-- Informações -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tipo de Conta</p>
                        @if($user->role === 'admin')
                            <p class="text-purple-600 font-bold flex items-center mt-1">
                                <span class="bg-purple-100 px-3 py-1 rounded-full">👑 Administrador</span>
                            </p>
                        @else
                            <p class="text-blue-600 font-bold flex items-center mt-1">
                                <span class="bg-blue-100 px-3 py-1 rounded-full">🎓 Aluno</span>
                            </p>
                        @endif
                    </div>

                    @if($user->rm)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">RM (Registro)</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $user->rm }}</p>
                    </div>
                    @endif

                    @if($user->ano_escolar)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Ano Escolar</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $user->ano_escolar }}º ano</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Membro desde</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($user->role === 'aluno')
                <!-- Estatísticas do Aluno -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-3">📊 Minhas Estatísticas</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Empréstimos Ativos:</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">
                                {{ $user->loans()->where('status', 'ativo')->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Total de Empréstimos:</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">
                                {{ $user->loans()->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Reservas Pendentes:</span>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">
                                {{ $user->reservations()->where('status', 'pendente')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Coluna Direita - Formulário de Edição -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Editar Informações</h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto de Perfil -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">📷 Foto de Perfil</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-300">
                                @if($user->photo)
                                    <img id="preview" src="{{ asset('storage/' . $user->photo) }}" 
                                         alt="Preview" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div id="preview-default" class="w-full h-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                        <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" id="photo" accept="image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                       onchange="previewPhoto(event)">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WEBP. Máximo 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Nome -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Nome Completo *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                   required>
                        </div>

                        <!-- Senha -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nova Senha</label>
                            <input type="password" name="password" 
                                   placeholder="Deixe em branco para não alterar"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <!-- Confirmar Senha -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" 
                                   placeholder="Confirme a nova senha"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Informações Somente Leitura -->
                    @if($user->rm || $user->ano_escolar || $user->role)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-3">ℹ️ Informações Protegidas</h3>
                        <p class="text-xs text-gray-600 mb-4">Estes dados não podem ser alterados por você. Entre em contato com a administração se precisar modificá-los.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($user->rm)
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-500 uppercase font-semibold">RM</p>
                                <p class="text-gray-800 font-semibold">{{ $user->rm }}</p>
                            </div>
                            @endif

                            @if($user->ano_escolar)
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Ano Escolar</p>
                                <p class="text-gray-800 font-semibold">{{ $user->ano_escolar }}º ano</p>
                            </div>
                            @endif

                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Tipo de Conta</p>
                                <p class="text-gray-800 font-semibold">{{ $user->role === 'admin' ? 'Administrador' : 'Aluno' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-8 flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            💾 Salvar Alterações
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewPhoto(event) {
        const preview = document.getElementById('preview');
        const defaultPreview = document.getElementById('preview-default');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (defaultPreview) {
                    defaultPreview.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection