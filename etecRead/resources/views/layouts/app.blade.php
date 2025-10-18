<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    @auth
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                            class="text-xl font-bold text-blue-600">
                            📚 Biblioteca
                        </a>

                        @if(auth()->user()->role === 'aluno')
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('livros.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('livros.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Livros
                            </a>
                            <a href="{{ route('emprestimos.meus') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('emprestimos.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Meus Empréstimos
                            </a>
                            <a href="{{ route('reservas.minhas') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('reservas.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Minhas Reservas
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.livros.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.livros.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Livros
                            </a>
                            <a href="{{ route('admin.categorias.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.categorias.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Categorias
                            </a>
                            <a href="{{ route('admin.usuarios.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.usuarios.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Usuários
                            </a>
                            <a href="{{ route('admin.emprestimos.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.emprestimos.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Empréstimos
                            </a>
                            <!-- ✅ NOVO -->
                            <a href="{{ route('admin.reservas.index') }}"
                                class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.reservas.*') ? 'text-blue-600 font-semibold' : '' }}">
                                Reservas
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <!-- Foto do usuário (clicável) -->
                            <a href="{{ route('perfil.show') }}"
                                class="flex items-center space-x-3 hover:opacity-80 transition">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                        <span
                                            class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Bem-vindo,</p>
                                    <p class="text-gray-700 font-semibold">{{ auth()->user()->name }}</p>
                                </div>
                            </a>
                        </div>

                        <!-- Botão Perfil -->
                        <a href="{{ route('perfil.show') }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            👤 Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Mensagens de Sucesso/Erro -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-12 py-6 border-t">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Sistema de Biblioteca Escolar. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>