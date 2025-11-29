@extends('layouts.app')

@section('title', 'Adicionar Usuário')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('admin.usuarios.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
                ← Voltar para lista
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Adicionar Novo Usuário</h1>
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

            <form method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- ✅ Preview da Foto -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Foto de Perfil</label>
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div id="preview-container"
                                class="w-32 h-32 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full flex items-center justify-center overflow-hidden">
                                <svg id="default-icon" class="w-16 h-16 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-2">Formatos aceitos: JPG, PNG, WEBP. Máximo: 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nome -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Senha -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Senha *</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <!-- RM -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">RM (Registro do Aluno)</label>
                        <input type="text" name="rm" value="{{ old('rm') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipo de Usuário *</label>
                        <select name="role" id="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value="">Selecione...</option>
                            <option value="aluno" {{ old('role') == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>

                    <!-- Ano Escolar -->
                    <div id="ano-escolar-field">
                        <label class="block text-gray-700 font-semibold mb-2">Ano Escolar</label>
                        <select name="ano_escolar"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Selecione...</option>
                            <option value="1" {{ old('ano_escolar') == '1' ? 'selected' : '' }}>1º ano</option>
                            <option value="2" {{ old('ano_escolar') == '2' ? 'selected' : '' }}>2º ano</option>
                            <option value="3" {{ old('ano_escolar') == '3' ? 'selected' : '' }}>3º ano</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Salvar Usuário
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview da imagem
        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const defaultIcon = document.getElementById('default-icon');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    defaultIcon.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Mostrar/ocultar campo ano_escolar baseado no role
        const roleSelect = document.getElementById('role');
        const anoEscolarField = document.getElementById('ano-escolar-field');

        roleSelect.addEventListener('change', function () {
            if (this.value === 'aluno') {
                anoEscolarField.style.display = 'block';
            } else {
                anoEscolarField.style.display = 'none';
            }
        });

        // Trigger no carregamento
        if (roleSelect.value !== 'aluno') {
            anoEscolarField.style.display = 'none';
        }
    </script>
@endsection