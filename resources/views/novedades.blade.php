<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/novedades.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    <header class="main-header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="site-title">
                <img src="iconotlabaho.webp" alt="Logo MEGABONK GUIDE" id="header-logo">
                <a href="{{ route('home') }}" class="site-title">MEGABONK GUIDE</a>

                <nav class="main-nav">
                    <ul>
                        <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                        <li><a href="{{ route('builds.index') }}">BUILDS</a></li>
                        <li><a href="{{ route('comunity.index') }}">COMMUNITY</a></li>
                        <li class="dropdown">
                            <a href="{{ route('unlocks.index') }}">UNLOCKS ▼</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('unlocks.weapons') }}">Armas</a></li>
                                <li><a href="{{ route('unlocks.tomes') }}">Tomos</a></li>
                                <li><a href="{{ route('unlocks.items') }}">Items</a></li>
                                <li><a href="{{ route('unlocks.characters') }}">Personajes</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('meta') }}">META</a></li>
                        <li><a href="{{ route('wiki.index') }}">INFO GENERAL</a></li>
                        <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                        <li><a href="{{ route('leaderboard') }}">LEADERBOARD</a></li>
                    </ul>
                </nav>
                <div class="user-auth-links">
                    <a href="{{ route('login') }}" class="auth-link">Login</a> |
                    <a href="{{ route('register') }}" class="auth-link">Registro</a>
                </div>
        </div>
    </header>

    <main class="main-content-news">

        <h1 class="page-title">📣 Últimas Novedades de MEGABONK</h1>

        <p class="intro-text-news">
            Mantente al día con los últimos parches, eventos especiales y anuncios oficiales. ¡Las novedades se listan
            de forma cronológica!
        </p>

        <section class="news-timeline">

            <div class="timeline-item">
                <div class="date-tag">9 DIC 2025</div>
                <div class="news-card current">
                    <h2>PATCH 3.2: La Furia del Arquitecto</h2>
                    <span class="news-type tag-patch">Parche Mayor</span>
                    <p>
                        ¡El parche 3.2 ya está aquí! Se ha introducido el nuevo Personaje "El Arquitecto" y se han
                        implementado cambios importantes en el escalado de vida del modo Bonk+. La Meta ha cambiado
                        drásticamente.
                    </p>
                    <ul class="patch-details">
                        <li class="buff">Nuevo Tomo: "Códice de la Suerte"</li>
                        <li class="nerf">Nerf al Anillo del Bonk Crítico (probabilidad reducida al 5%).</li>
                    </ul>
                    <a href="#" class="read-more">Ver Patch Notes Completas →</a>
                </div>
            </div>

            <div class="timeline-item">
                <div class="date-tag">15 NOV 2025</div>
                <div class="news-card">
                    <h2>Evento Especial: El Desafío de Noviembre</h2>
                    <span class="news-type tag-event">Evento</span>
                    <p>
                        ¡El evento de tiempo limitado ha comenzado! Completa la mazmorra especial para obtener el Item
                        Único "Gema Congelada".
                    </p>
                    <a href="#" class="read-more">Detalles del Evento →</a>
                </div>
            </div>

            <div class="timeline-item">
                <div class="date-tag">28 OCT 2025</div>
                <div class="news-card">
                    <h2>PATCH 3.1: Equilibrio y Estabilidad</h2>
                    <span class="news-type tag-patch">Parche Menor</span>
                    <p>
                        Ajustes menores de equilibrio, incluyendo un buff al Berserker y corrección de errores críticos
                        en el modo Leaderboard.
                    </p>
                </div>
            </div>

        </section>

        <div class="archive-cta">
            <a href="#" class="btn-archive">Ver Archivo Histórico de Novedades</a>
        </div>

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
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>

</body>

</html>