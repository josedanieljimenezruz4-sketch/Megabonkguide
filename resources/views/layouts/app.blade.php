<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MEGABONK GUIDE')</title>
    
    <!-- CSS Globales -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <!-- Espacio para inyectar CSS específicos por vista -->
    @stack('styles')
    
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
    <!-- CSS Global Atmosférico -->
    <style>
        body {
            background: radial-gradient(circle at top left, rgba(255, 0, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(0, 255, 255, 0.05), transparent 40%),
                        #080808 !important;
            background-attachment: fixed !important;
            background-color: #080808 !important;
            min-height: 100vh;
            margin: 0;
            color: white;
        }

        .glass-card {
            background: rgba(15, 15, 15, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(0, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>

    <!-- Header común -->
    @include('partials.header')

    <!-- CONTENIDO DINÁMICO -->
    @yield('content')

    <!-- Footer común -->
    <footer class="main-footer">
        <div class="footer-sections">
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                    <li><a href="{{ route('meta') }}">META</a></li>
                    <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                </ul>
            </div>
            <div>
                <h3>Soporte</h3>
                <ul>
                    <li><a href="#">Contáctanos</a></li>
                    <li><a href="{{ route('comunity.suggestions') }}">Sugerencias</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div>
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>

    <!-- Espacio para inyectar JavaScript específico por vista -->
    @stack('scripts')
</body>
</html>
