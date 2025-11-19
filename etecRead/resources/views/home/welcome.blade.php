<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtecRead - Sistema de Biblioteca Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        
        /* Gradiente animado */
        .animated-gradient {
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
<body class="bg-gray-900">

    @auth
        <!-- Navbar -->
        <nav class="bg-[#2a2a2a] shadow-lg">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="bg-red-600 text-white w-10 h-10 rounded-md flex items-center justify-center font-bold text-base">
                            ER
                        </div>
                        <span class="text-xl font-semibold text-white">EtecRead</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="px-4 py-2 text-white bg-white/10 rounded-lg font-medium transition">
                            Home
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-300 hover:text-white font-medium transition">
                            Dashboard
                        </a>
                        <a href="{{ route('livros.index') }}" class="px-4 py-2 text-gray-300 hover:text-white font-medium transition">
                            Livros
                        </a>
                        <a href="{{ route('emprestimos.meus') }}" class="px-4 py-2 text-gray-300 hover:text-white font-medium transition">
                            Empréstimos
                        </a>
                        <a href="{{ route('reservas.minhas') }}" class="px-4 py-2 text-gray-300 hover:text-white font-medium transition">
                            Reservas
                        </a>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-3 focus:outline-none hover:opacity-80 transition">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover">
                            @else
                                <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <span class="text-white font-medium text-sm">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-xl overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
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

    <!-- Hero Section -->
    <div class="animated-gradient min-h-screen flex items-center justify-center px-4">
        <div class="max-w-7xl mx-auto w-full py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Side - Content -->
                <div class="text-white space-y-8">
                    <!-- Badge -->
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium">Sistema de Biblioteca Digital</span>
                    </div>

                    <!-- Title -->
                    <div>
                        <h1 class="text-5xl md:text-6xl font-bold mb-4 leading-tight">
                            Bem-vindo ao<br>
                            <span class="text-red-500">EtecRead</span>
                        </h1>
                        <p class="text-gray-300 text-lg leading-relaxed">
                            Explore nosso catálogo digital, gerencie seus empréstimos e descubra novos conhecimentos através da leitura. Tudo em um só lugar, de forma simples e intuitiva.
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('livros.index') }}" class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-4 rounded-lg transition shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Explorar Catálogo
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-lg transition border border-white/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Meu Dashboard
                        </a>
                    </div>
                </div>

                <!-- Right Side - Stats Cards -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Livros Disponíveis -->
                    <div class="bg-gradient-to-br from-red-500/20 to-red-600/10 backdrop-blur-sm border border-red-500/30 rounded-2xl p-6 hover:scale-105 transition transform">
                        <div class="bg-red-500/20 w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold text-white mb-2">500+</p>
                        <p class="text-gray-300 text-sm">Livros Disponíveis</p>
                    </div>

                    <!-- Leitores Ativos -->
                    <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/10 backdrop-blur-sm border border-blue-500/30 rounded-2xl p-6 hover:scale-105 transition transform">
                        <div class="bg-blue-500/20 w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold text-white mb-2">300+</p>
                        <p class="text-gray-300 text-sm">Leitores Ativos</p>
                    </div>

                    <!-- Categorias -->
                    <div class="bg-gradient-to-br from-purple-500/20 to-purple-600/10 backdrop-blur-sm border border-purple-500/30 rounded-2xl p-6 hover:scale-105 transition transform">
                        <div class="bg-purple-500/20 w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold text-white mb-2">50+</p>
                        <p class="text-gray-300 text-sm">Categorias</p>
                    </div>

                    <!-- Acesso Online -->
                    <div class="bg-gradient-to-br from-green-500/20 to-green-600/10 backdrop-blur-sm border border-green-500/30 rounded-2xl p-6 hover:scale-105 transition transform">
                        <div class="bg-green-500/20 w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold text-white mb-2">24/7</p>
                        <p class="text-gray-300 text-sm">Acesso Online</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Resources Section -->
    <div class="bg-white py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-3">Recursos Disponíveis</p>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">O que você pode fazer</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Aproveite todos os recursos disponíveis para facilitar sua experiência com a biblioteca
                </p>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Explorar Livros -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition group">
                    <div class="bg-gray-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-gray-900 transition">
                        <svg class="w-8 h-8 text-gray-700 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Explorar Livros</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Navegue por nosso catálogo completo e encontre o livro perfeito para você
                    </p>
                    <a href="{{ route('livros.index') }}" class="text-gray-900 font-semibold text-sm inline-flex items-center group-hover:text-red-600 transition">
                        Ver catálogo →
                    </a>
                </div>

                <!-- Acompanhar Empréstimos -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition group">
                    <div class="bg-blue-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition">
                        <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Acompanhar Empréstimos</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Gerencie seus empréstimos ativos e veja seu histórico completo
                    </p>
                    <a href="{{ route('emprestimos.meus') }}" class="text-gray-900 font-semibold text-sm inline-flex items-center group-hover:text-blue-600 transition">
                        Ver empréstimos →
                    </a>
                </div>

                <!-- Fazer Reservas -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition group">
                    <div class="bg-purple-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition">
                        <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Fazer Reservas</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Reserve livros indisponíveis e seja notificado quando estiverem disponíveis
                    </p>
                    <a href="{{ route('reservas.minhas') }}" class="text-gray-900 font-semibold text-sm inline-flex items-center group-hover:text-purple-600 transition">
                        Ver reservas →
                    </a>
                </div>

                <!-- Gerenciar Perfil -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition group">
                    <div class="bg-green-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg class="w-8 h-8 text-green-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Gerenciar Perfil</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Atualize suas informações e acompanhe suas estatísticas de leitura
                    </p>
                    <a href="{{ route('perfil.show') }}" class="text-gray-900 font-semibold text-sm inline-flex items-center group-hover:text-green-600 transition">
                        Ver perfil →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
<div class="relative overflow-hidden py-24 px-4">
    <!-- Background com gradiente animado -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
    
    <!-- Pattern overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <!-- Decorative elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-red-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

    <!-- Content -->
    <div class="relative max-w-4xl mx-auto text-center z-10">
        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/20">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-sm font-medium text-white">Comece sua jornada literária agora</span>
        </div>

        <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
            <span class="gradient-text-animated">Pronto para começar?</span>
        </h2>
        
        <p class="text-gray-300 text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
            Explore nosso acervo e encontre o próximo livro que vai <strong class="text-white">transformar</strong> sua jornada de aprendizado
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('livros.index') }}" class="group relative inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-4 rounded-xl transition shadow-2xl hover:shadow-red-500/50 text-lg overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-500 opacity-0 group-hover:opacity-100 transition"></span>
                <svg class="w-6 h-6 mr-2 relative z-10 group-hover:rotate-12 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="relative z-10">Ver Catálogo Completo</span>
            </a>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold px-10 py-4 rounded-xl transition border border-white/20 hover:border-white/40 text-lg">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Ir ao Dashboard
            </a>
        </div>

        <!-- Stats mini -->
        <div class="grid grid-cols-3 gap-8 mt-16 max-w-3xl mx-auto">
            <div class="text-center">
                <p class="text-4xl font-bold text-white mb-2">500+</p>
                <p class="text-gray-400 text-sm">Livros</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-white mb-2">300+</p>
                <p class="text-gray-400 text-sm">Leitores</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-white mb-2">24/7</p>
                <p class="text-gray-400 text-sm">Disponível</p>
            </div>
        </div>
    </div>

    <style>
        .gradient-text-animated {
            background: linear-gradient(90deg, 
                #ffffff 0%, 
                #fca5a5 20%,
                #ef4444 40%, 
                #dc2626 50%,
                #ef4444 60%, 
                #fca5a5 80%,
                #ffffff 100%
            );
            background-size: 200% auto;
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
            animation: gradient-shift 4s linear infinite;
            font-weight: 700;
        }
        
        @keyframes gradient-shift {
            0% {
                background-position: 200% center;
            }
            100% {
                background-position: 0% center;
            }
        }
    </style>
</div>

    <!-- Footer -->
    <footer class="bg-[#0f0f0f] text-white border-t border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">
                © {{ date('Y') }} <span class="font-semibold text-white">EtecRead</span> - Sistema de Biblioteca. Todos os direitos reservados.
            </p>
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