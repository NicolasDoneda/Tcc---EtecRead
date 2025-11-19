<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EtecRead - Biblioteca')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Gradiente animado na navbar */
        .navbar-gradient {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #1a1a1a 50%, #2d2d2d 75%, #1a1a1a 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-gray-50">

    @auth
        <!-- Navbar -->
        <nav class="navbar-gradient shadow-xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home') }}" 
                       class="flex items-center space-x-3 group">
                        <div class="bg-red-600 text-white w-10 h-10 rounded-md flex items-center justify-center font-bold text-base group-hover:bg-red-700 transition shadow-lg">
                            ER
                        </div>
                        <span class="text-xl font-semibold text-white group-hover:text-red-400 transition">EtecRead</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-2">
                        @if(auth()->user()->role === 'aluno')
                            <a href="{{ route('home') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('home') ? 'bg-white/10 text-white' : '' }}">
                                Home
                            </a>
                            <a href="{{ route('dashboard') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('livros.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('livros.*') ? 'bg-white/10 text-white' : '' }}">
                                Livros
                            </a>
                            <a href="{{ route('emprestimos.meus') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('emprestimos.*') ? 'bg-white/10 text-white' : '' }}">
                                Empréstimos
                            </a>
                            <a href="{{ route('reservas.minhas') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('reservas.*') ? 'bg-white/10 text-white' : '' }}">
                                Reservas
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.livros.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.livros.*') ? 'bg-white/10 text-white' : '' }}">
                                Livros
                            </a>
                            <a href="{{ route('admin.autores.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.autores.*') ? 'bg-white/10 text-white' : '' }}">
                                Autores
                            </a>
                            <a href="{{ route('admin.categorias.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.categorias.*') ? 'bg-white/10 text-white' : '' }}">
                                Categorias
                            </a>
                            <a href="{{ route('admin.usuarios.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.usuarios.*') ? 'bg-white/10 text-white' : '' }}">
                                Usuários
                            </a>
                            <a href="{{ route('admin.emprestimos.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.emprestimos.*') ? 'bg-white/10 text-white' : '' }}">
                                Empréstimos
                            </a>
                            <a href="{{ route('admin.reservas.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg font-medium transition {{ request()->routeIs('admin.reservas.*') ? 'bg-white/10 text-white' : '' }}">
                                Reservas
                            </a>
                        @endif
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-3 bg-white/5 hover:bg-white/10 px-3 py-2 rounded-lg focus:outline-none transition group">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-9 h-9 rounded-full object-cover ring-2 ring-white/20 group-hover:ring-white/40 transition">
                            @else
                                <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center ring-2 ring-white/20 group-hover:ring-white/40 transition">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <span class="text-white font-medium text-sm">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-xl overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('perfil.show') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium">Meu Perfil</span>
                            </a>
                            <div class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Sair</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-8 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1a1a1a] text-white border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="text-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} <span class="font-semibold text-white">EtecRead</span> - Sistema de Biblioteca. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('show');
        }

        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!e.target.closest('button') && !e.target.closest('#dropdownMenu')) {
                dropdown.classList.remove('show');
            }
        });
    </script>

</body>
</html>