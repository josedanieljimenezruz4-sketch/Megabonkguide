<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlocks | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-unlocks">

        <h1 class="page-title">🔓 Explorador de Unlocks</h1>

        <p class="intro-text-unlocks">
            Bienvenido al índice de todo el contenido desbloqueable en MEGABONK.
            Aquí encontrarás guías completas sobre cómo obtener y utilizar cada Personaje, Arma, Tomo e Item para
            dominar el juego.
        </p>

        <section class="unlocks-grid">

            <a href="{{ route('unlocks.characters') }}" class="unlock-card card-personajes">
                <h2>Personajes</h2>
                <p>Descubre a todos los héroes, sus habilidades únicas y cómo desbloquearlos.</p>
                <span class="card-icon">👤</span>
            </a>

            <a href="{{ route('unlocks.weapons') }}" class="unlock-card card-armas">
                <h2>Armas</h2>
                <p>Análisis de estadísticas, efectos de Bonk y rutas de obtención para cada arma.</p>
                <span class="card-icon">⚔️</span>
            </a>

            <a href="{{ route('unlocks.tomes') }}" class="unlock-card card-tomos">
                <h2>Tomos</h2>
                <p>Revisa la lista de todos los tomos y sus efectos permanentes en tus Builds.</p>
                <span class="card-icon">📜</span>
            </a>

            <a href="{{ route('unlocks.items') }}" class="unlock-card card-items">
                <h2>Items</h2>
                <p>Guías sobre los items legendarios, raros y de uso único. ¡No te pierdas ninguno!</p>
                <span class="card-icon">💎</span>
            </a>

        </section>

        <section class="notes-section">
            <p>
                *Nota: Para ver un Personaje, Arma, Tomo o Item específico, por favor haz click en la categoría
                correspondiente.
                Si has iniciado sesión, puedes ver tu progreso de desbloqueo en la página de Perfil.
            </p>
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