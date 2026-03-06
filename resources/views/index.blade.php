<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
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
            <a href="{{ route('info.general') }}" class="btn btn-info">💡 Información General</a>
        </section>

    </main>

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
        <div class="footer-copy">
            &copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.
        </div>
    </footer>

</body>

</html>