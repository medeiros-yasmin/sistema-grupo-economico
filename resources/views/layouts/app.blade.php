<!DOCTYPE html>
<html>
<head>
    <title>Sistema Grupo Econômico</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Adicione esta linha para os ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">Sistema Grupo Econômico</a>
            
            <!-- Menu de usuário -->
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        👤 {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Cadastrar</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="container py-4">
        @if(!empty(trim($slot ?? '')))
        {{ $slot }}
    @else
        @yield('content')
    @endif
    </main>
</body>
</html>