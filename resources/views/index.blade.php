<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>

<body>

    @include('partials.header')

    <main class="main-content-container">

        <section class="intro-section">
            <h1 class="main-page-title">MEGABONK GUIDE</h1>

            <p class="intro-text">
                Megabonk Guide es una página informativa dedicada a guías, **builds**, contenido del juego y
                herramientas para mejorar en Megabonk.
            </p>
        </section>

        <section class="unlocks-examples-section">
            <h2>✨ Contenido Destacado de Unlocks</h2>
            <ul class="unlocks-list">
                <li><a href="{{ route('unlocks.index') }}">Nuevo Personaje: La Maestra del Bonk</a></li>
                <li><a href="{{ route('unlocks.index') }}">Arma Rara: Hacha de Púrpura Radiante</a></li>
                <li><a href="{{ route('unlocks.index') }}">Tomo Legendario: El Códice de la Velocidad</a></li>
                <li><a href="{{ route('unlocks.index') }}">Item Único: El Anillo del Bonk Crítico</a></li>
            </ul>
        </section>

        <section class="action-buttons">
            <a href="{{ route('comunity.suggestions') }}" class="btn btn-sugerencias">📧 Sugerencias</a>
            <a href="{{ route('wiki.index') }}" class="btn btn-info">💡 Información General</a>
        </section>

    </main>

    @include('partials.footer')

</body>

</html>
