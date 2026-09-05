<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proyectos - Tech Solutions</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <header class="topbar">
        <a href="{{ session('usuario_id') ? route('projects.index') : route('login') }}" class="brand">Tech Solutions · Gestión de Proyectos</a>

        <nav>
            @if (session('usuario_id'))
                <span class="usuario-actual">Hola, {{ session('usuario_nombre') }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="link-button">Cerrar sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endif
        </nav>
    </header>

    <x-uf-widget />

    @if (session('success'))
        <div class="popup popup-success" id="popup-mensaje">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="popup popup-error" id="popup-mensaje">
            {{ session('error') }}
        </div>
    @endif

    @yield('contenido')

    <script>
        const popup = document.getElementById('popup-mensaje');

        if (popup) {
            setTimeout(function () {
                popup.remove();
            }, 3000);
        }
    </script>

</body>
</html>