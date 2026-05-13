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
    
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>
<body>

    <!-- Header común -->
    @include('partials.header')

    <!-- CONTENIDO DINÁMICO -->
    @yield('content')

    <!-- Footer común -->
    @include('partials.footer')

    <!-- Espacio para inyectar JavaScript específico por vista -->
    @stack('scripts')
</body>
</html>
