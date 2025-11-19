@extends('layouts.app')

@section('title', 'Adicionar Autor')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.autores.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Adicionar Autor</h1>
                <p class="text-gray-600 mt-1">Preencha as informações do novo autor</p>
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
                    <p class="text-red-800 font-medium mb-2">Erro ao criar autor:</p>
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
    <form method="POST" action="{{ route('admin.autores.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-md p-8 space-y-6">
            
            <!-- Foto do Autor -->
            <div>
                <label class="block text-gray-900 font-bold mb-3">Foto do Autor</label>
                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full overflow-hidden flex-shrink-0">
                        <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <div id="preview-default" class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition"
                               onchange="previewImage(event)">
                        <p class="text-sm text-gray-500 mt-2">JPG, PNG ou WEBP. Máximo 2MB</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6"></div>

            <!-- Nome -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Nome Completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition" 
                       required>
            </div>

            <!-- Biografia -->
            <div>
                <label class="block text-gray-900 font-bold mb-2">Biografia</label>
                <textarea name="bio" rows="6" 
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition">{{ old('bio') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Informações sobre a vida e obra do autor</p>
            </div>

            <!-- Datas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Data de Nascimento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition">
                </div>

                <div>
                    <label class="block text-gray-900 font-bold mb-2">Data de Falecimento</label>
                    <input type="date" name="death_date" value="{{ old('death_date') }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition">
                    <p class="text-sm text-gray-500 mt-1">Deixe em branco se o autor estiver vivo</p>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Criar Autor
                </button>
                <a href="{{ route('admin.autores.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
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
                defaultPreview.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection