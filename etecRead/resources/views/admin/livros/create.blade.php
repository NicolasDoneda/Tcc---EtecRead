@extends('layouts.app')

@section('title', 'Adicionar Livro')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.livros.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Adicionar Livro</h1>
                    <p class="text-gray-600 mt-1">Preencha as informações do novo livro</p>
                </div>
            </div>
        </div>

        <!-- Erros -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-red-800 font-medium mb-2">Erro ao criar livro:</p>
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
        <form method="POST" action="{{ route('admin.livros.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-xl shadow-md p-8 space-y-6">

                <!-- Capa do Livro -->
                <div>
                    <label class="block text-gray-900 font-bold mb-3">Capa do Livro</label>
                    <div class="flex items-center gap-6">
                        <div
                            class="w-32 h-40 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg overflow-hidden flex-shrink-0">
                            <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="preview-default" class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                onchange="previewImage(event)">
                            <p class="text-sm text-gray-500 mt-2">JPG, PNG ou WEBP. Máximo 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6"></div>

                <!-- Título -->
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Título *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                </div>

                <!-- Categoria e ISBN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-900 font-bold mb-2">Categoria *</label>
                        <select name="category_id"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('category_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-900 font-bold mb-2">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Autores -->
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Autores</label>
                    <select name="authors[]" id="authors-select" multiple>
                        @foreach($autores as $autor)
                            <option value="{{ $autor->id }}" {{ in_array($autor->id, old('authors', [])) ? 'selected' : '' }}>
                                {{ $autor->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-2">Digite para buscar e selecionar múltiplos autores</p>
                </div>

                <!-- Ano e Quantidades -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-900 font-bold mb-2">Ano de Publicação</label>
                        <input type="number" name="year" value="{{ old('year') }}" min="1000" max="{{ date('Y') }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-gray-900 font-bold mb-2">Quantidade Total *</label>
                        <input type="number" name="total_quantity" value="{{ old('total_quantity', 1) }}" min="0"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-900 font-bold mb-2">Quantidade Disponível *</label>
                        <input type="number" name="available_quantity" value="{{ old('available_quantity', 1) }}" min="0"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Descrição</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('description') }}</textarea>
                </div>

                <!-- Notas -->
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Notas/Observações</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('notes') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Informações adicionais sobre o livro</p>
                </div>

                <!-- Botões -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Criar Livro
                    </button>
                    <a href="{{ route('admin.livros.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
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

    // Choices.js para select de autores
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('authors-select');
        const choices = new Choices(element, {
            removeItemButton: true,
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar autor...',
            noResultsText: 'Nenhum autor encontrado',
            itemSelectText: 'Clique para selecionar',
            placeholderValue: 'Selecione os autores',
            maxItemCount: 10,
            shouldSort: true,
        });
    });
</script>
@endsection