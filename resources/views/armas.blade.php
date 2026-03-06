<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armas | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-catalogo">

        <h1 class="page-title">⚔️ Catálogo de Armas</h1>

        <p class="catalogo-intro">
            Explora todas las armas, sus estadísticas base y las condiciones necesarias para añadirlas a tu colección.
        </p>

        <section class="catalogo-grid">

            <a href="#" class="item-card card-arma">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-hacha-purpura" name="unl-hacha-purpura">
                    <label for="unl-hacha-purpura" class="checkbox-label"></label>
                </div>
                <span class="card-icon">🔥</span>
                <h2>Hacha Púrpura Radiante</h2>
                <p>Tipo: Hacha | Daño Base: Alto</p>
                <p class="unlock-req">Requisito: Encontrar los 3 Tomos del Poder en una sola partida.</p>
            </a>

            <a href="#" class="item-card card-arma">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-baston-cobre" name="unl-baston-cobre" checked>
                    <label for="unl-baston-cobre" class="checkbox-label"></label>
                </div>
                <span class="card-icon">⚡</span>
                <h2>Bastón del Cobre Rápido</h2>
                <p>Tipo: Bastón | Daño Base: Bajo | Velocidad: Muy Alta</p>
                <p class="unlock-req">Requisito: Sobrevivir 30 minutos sin matar enemigos.</p>
            </a>

            <a href="#" class="item-card card-arma">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-mazo-guerra" name="unl-mazo-guerra">
                    <label for="unl-mazo-guerra" class="checkbox-label"></label>
                </div>
                <span class="card-icon">🔨</span>
                <h2>Mazo de Guerra Bonker</h2>
                <p>Tipo: Mazo | Daño Base: Extremo</p>
                <p class="unlock-req">Requisito: Lograr 100 golpes críticos en total.</p>
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