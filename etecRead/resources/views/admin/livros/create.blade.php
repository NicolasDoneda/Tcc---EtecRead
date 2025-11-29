@extends('layouts.app')

@section('title', 'Adicionar Livro')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('admin.livros.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
                ← Voltar para lista
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Adicionar Novo Livro</h1>
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

            <form method="POST" action="{{ route('admin.livros.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- ✅ Preview da Imagem -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Capa do Livro</label>
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <div id="preview-container"
                                class="w-48 h-64 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center overflow-hidden">
                                <svg id="default-icon" class="w-24 h-24 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-2">Formatos aceitos: JPG, PNG, WEBP. Máximo: 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Título -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Título *</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Categoria *</label>
                        <select name="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('category_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Ano -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Ano de Publicação</label>
                        <input type="number" name="year" value="{{ old('year') }}" min="1000" max="{{ date('Y') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Quantidade Total -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantidade Total *</label>
                        <input type="number" name="total_quantity" value="{{ old('total_quantity', 1) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Quantidade Disponível -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantidade Disponível *</label>
                        <input type="number" name="available_quantity" value="{{ old('available_quantity', 1) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Deve ser menor ou igual à quantidade total</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Salvar Livro
                    </button>
                    <a href="{{ route('admin.livros.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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
    </script>
@endsection