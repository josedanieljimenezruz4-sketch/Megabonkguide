<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-catalogo">

        <h1 class="page-title">💎 Colección de Ítems</h1>

        <p class="catalogo-intro">
            Los Ítems ofrecen poderosos efectos pasivos. Aquí están las guías para obtener cada uno.
        </p>

        <section class="catalogo-grid">

            <a href="#" class="item-card card-item">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-anillo-critico" name="unl-anillo-critico" checked>
                    <label for="unl-anillo-critico" class="checkbox-label"></label>
                </div>
                <span class="card-icon">💍</span>
                <h2>Anillo del Bonk Crítico</h2>
                <p>Efecto: +10% de Probabilidad Crítica</p>
                <p class="unlock-req">Requisito: Lograr 50 golpes críticos consecutivos.</p>
            </a>

            <a href="#" class="item-card card-item">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-moneda-avaro" name="unl-moneda-avaro">
                    <label for="unl-moneda-avaro" class="checkbox-label"></label>
                </div>
                <span class="card-icon">💰</span>
                <h2>Moneda del Avaro</h2>
                <p>Efecto: +20% de Oro al Matar</p>
                <p class="unlock-req">Requisito: Acumular 10,000 de Oro en una sola partida.</p>
            </a>

            <a href="#" class="item-card card-item">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-armadura-olvido" name="unl-armadura-olvido">
                    <label for="unl-armadura-olvido" class="checkbox-label"></label>
                </div>
                <span class="card-icon">🧊</span>
                <h2>Armadura del Olvido</h2>
                <p>Efecto: Congela al enemigo al recibir daño.</p>
                <p class="unlock-req">Requisito: Derrotar a 100 enemigos tipo Golem.</p>
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