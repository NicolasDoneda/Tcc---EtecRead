@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Editar Usuário</h1>
                <p class="text-gray-600 mt-1">{{ $usuario->name }}</p>
            </div>
        </div>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-red-800 font-medium mb-2">Erro ao atualizar usuário:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-md p-8 space-y-6">
            
            <!-- Foto do Usuário -->
            <div>
                <label class="block text-gray-900 font-bold mb-3">Foto do Usuário</label>
                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 bg-gradient-to-br from-slate-500 to-slate-700 rounded-full overflow-hidden flex-shrink-0">
                        @if($usuario->photo)
                            <img id="preview" src="{{ asset('storage/' . $usuario->photo) }}" 
                                 alt="{{ $usuario->name }}" 
                                 class="w-full h-full object-cover">
                            <div id="preview-default" class="hidden"></div>
                        @else
                            <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="preview-default" class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition"
                               onchange="previewImage(event)">
                        <p class="text-sm text-gray-500 mt-2">JPG, PNG ou WEBP. Máximo 2MB. Deixe em branco para manter a imagem atual.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6"></div>

            <!-- Nome -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Nome Completo *</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition" 
                       required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition" 
                       required>
            </div>

            <!-- Senha -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Nova Senha</label>
                <input type="password" name="password" 
                       placeholder="Deixe em branco para não alterar"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition">
                <p class="text-sm text-gray-500 mt-1">Mínimo 6 caracteres. Deixe em branco para manter a senha atual.</p>
            </div>

            <!-- Tipo de Conta e RM -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Tipo de Conta *</label>
                    <select name="role" id="role" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition" 
                            required onchange="toggleAnoEscolar()">
                        <option value="">Selecione...</option>
                        <option value="aluno" {{ old('role', $usuario->role) == 'aluno' ? 'selected' : '' }}>Aluno</option>
                        <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-900 font-bold mb-2">RM (Registro)</label>
                    <input type="text" name="rm" value="{{ old('rm', $usuario->rm) }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition">
                    <p class="text-sm text-gray-500 mt-1">Opcional</p>
                </div>
            </div>

            <!-- Ano Escolar (só aparece se for aluno) -->
            <div id="ano-escolar-field" style="display: {{ old('role', $usuario->role) == 'aluno' ? 'block' : 'none' }};">
                <label class="block text-gray-900 font-bold mb-2">Ano Escolar</label>
                <select name="ano_escolar" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('ano_escolar', $usuario->ano_escolar) == '1' ? 'selected' : '' }}>1º ano</option>
                    <option value="2" {{ old('ano_escolar', $usuario->ano_escolar) == '2' ? 'selected' : '' }}>2º ano</option>
                    <option value="3" {{ old('ano_escolar', $usuario->ano_escolar) == '3' ? 'selected' : '' }}>3º ano</option>
                </select>
            </div>

            <!-- Info sobre empréstimos -->
            @if($usuario->loans()->where('status', 'ativo')->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-yellow-800 font-medium">Este usuário possui {{ $usuario->loans()->where('status', 'ativo')->count() }} empréstimo(s) ativo(s).</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botões -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
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

    function toggleAnoEscolar() {
        const role = document.getElementById('role').value;
        const anoEscolarField = document.getElementById('ano-escolar-field');
        
        if (role === 'aluno') {
            anoEscolarField.style.display = 'block';
        } else {
            anoEscolarField.style.display = 'none';
        }
    }
</script>
@endsection