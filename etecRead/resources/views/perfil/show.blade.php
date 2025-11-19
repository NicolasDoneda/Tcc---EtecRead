@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Meu Perfil</h1>
        <p class="text-gray-600">Gerencie suas informações pessoais e preferências</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Coluna Esquerda - Card de Perfil -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-8">
                <!-- Header do Card -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 h-32"></div>
                
                <!-- Foto de Perfil -->
                <div class="relative px-6 pb-6">
                    <div class="flex flex-col items-center -mt-16">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl mb-4">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-5xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 text-center">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>

                        <!-- Badge do Tipo de Conta -->
                        <div class="mt-4">
                            @if($user->role === 'admin')
                                <span class="bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-full flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd"/>
                                    </svg>
                                    Administrador
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-full flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                    Aluno
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informações Detalhadas -->
                    <div class="space-y-4 mt-6 pt-6 border-t border-gray-200">
                        @if($user->rm)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">RM</p>
                                    <p class="text-gray-900 font-bold">{{ $user->rm }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($user->ano_escolar)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Ano Escolar</p>
                                    <p class="text-gray-900 font-bold">{{ $user->ano_escolar }}º ano</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Membro desde</p>
                                    <p class="text-gray-900 font-bold">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($user->role === 'aluno')
                    <!-- Estatísticas do Aluno -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Minhas Estatísticas
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-sm text-gray-700 font-medium">Empréstimos Ativos</span>
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $user->loans()->where('status', 'ativo')->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm text-gray-700 font-medium">Total de Empréstimos</span>
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $user->loans()->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <span class="text-sm text-gray-700 font-medium">Reservas Pendentes</span>
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $user->reservations()->where('status', 'pendente')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna Direita - Formulário de Edição -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Alertas -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-800 font-medium mb-2">Erro ao atualizar perfil:</p>
                            <ul class="list-disc list-inside text-red-700 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card de Edição -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Informações
                </h2>

                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto de Perfil -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl">
                        <label class=" text-gray-900 font-bold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Foto de Perfil
                        </label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg flex-shrink-0">
                                @if($user->photo)
                                    <img id="preview" src="{{ asset('storage/' . $user->photo) }}" 
                                         alt="Preview" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div id="preview-default" class="w-full h-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" id="photo" accept="image/*"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                       onchange="previewPhoto(event)">
                                <p class="text-sm text-gray-500 mt-2">JPG, PNG ou WEBP. Máximo 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        
                        <!-- Nome -->
                        <div>
                            <label class="block text-gray-900 font-bold mb-2">Nome Completo *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" 
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-gray-900 font-bold mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition" 
                                   required>
                        </div>

                        <!-- Senhas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-900 font-bold mb-2">Nova Senha</label>
                                <input type="password" name="password" 
                                       placeholder="••••••••"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                                <p class="text-xs text-gray-500 mt-1">Deixe em branco para não alterar</p>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-bold mb-2">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" 
                                       placeholder="••••••••"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                            </div>
                        </div>
                    </div>

                    <!-- Informações Protegidas -->
                    @if($user->rm || $user->ano_escolar || $user->role)
                    <div class="mt-8 p-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                        <div class="flex items-start mb-4">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Informações Protegidas</h3>
                                <p class="text-sm text-gray-700">Estes dados não podem ser alterados. Entre em contato com a administração se precisar modificá-los.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            @if($user->rm)
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">RM</p>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->rm }}</p>
                            </div>
                            @endif

                            @if($user->ano_escolar)
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Ano Escolar</p>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->ano_escolar }}º ano</p>
                            </div>
                            @endif

                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Tipo de Conta</p>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->role === 'admin' ? 'Administrador' : 'Aluno' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Botões -->
                    <div class="mt-8 flex gap-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Alterações
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
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