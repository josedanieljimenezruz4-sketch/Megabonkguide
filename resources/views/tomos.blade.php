<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomos | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-catalogo">

        <h1 class="page-title">📜 Biblioteca de Tomos</h1>

        <p class="catalogo-intro">
            Los Tomos ofrecen mejoras permanentes a tus personajes. Descubre cómo desbloquear cada volumen.
        </p>

        <section class="catalogo-grid">

            <a href="#" class="item-card card-tomo">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-codice-velocidad" name="unl-codice-velocidad">
                    <label for="unl-codice-velocidad" class="checkbox-label"></label>
                </div>
                <span class="card-icon">💨</span>
                <h2>Códice de la Velocidad</h2>
                <p>Efecto: +5% Velocidad de Ataque</p>
                <p class="unlock-req">Requisito: Correr 100km acumulados.</p>
            </a>

            <a href="#" class="item-card card-tomo">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-tomo-poder" name="unl-tomo-poder" checked>
                    <label for="unl-tomo-poder" class="checkbox-label"></label>
                </div>
                <span class="card-icon">💪</span>
                <h2>Tomo del Poder</h2>
                <p>Efecto: +10 Daño Base</p>
                <p class="unlock-req">Requisito: Derrotar al Jefe X 5 veces.</p>
            </a>

            <a href="#" class="item-card card-tomo">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-volumen-vida" name="unl-volumen-vida">
                    <label for="unl-volumen-vida" class="checkbox-label"></label>
                </div>
                <span class="card-icon">❤️</span>
                <h2>Volumen de la Vida</h2>
                <p>Efecto: +15 Salud Máxima</p>
                <p class="unlock-req">Requisito: Curarse 5000 puntos de vida.</p>
            </a>

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
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>
</body>

</html>