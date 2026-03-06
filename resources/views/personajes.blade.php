<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personajes | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-catalogo">

        <h1 class="page-title">👤 Todos los Personajes Desbloqueables</h1>

        <p class="catalogo-intro">
            A continuación, encontrarás el listado completo de todos los héroes jugables en MEGABONK, sus clases y cómo
            obtenerlos.
        </p>

        <section class="catalogo-grid">

            <a href="#" class="item-card card-personaje">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-maestra-bonk" name="unl-maestra-bonk" checked>
                    <label for="unl-maestra-bonk" class="checkbox-label"></label>
                </div>
                <span class="card-icon">👑</span>
                <h2>La Maestra del Bonk</h2>
                <p>Clase: DPS | Rareza: Legendaria</p>
                <p class="unlock-req">Requisito: Derrotar al Jefe Final sin recibir daño.</p>
            </a>

            <a href="#" class="item-card card-personaje">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-berserker" name="unl-berserker">
                    <label for="unl-berserker" class="checkbox-label"></label>
                </div>
                <span class="card-icon">🛡️</span>
                <h2>El Berserker</h2>
                <p>Clase: Tanque | Rareza: Rara</p>
                <p class="unlock-req">Requisito: Acumular 1,000,000 de daño bloqueado.</p>
            </a>

            <a href="#" class="item-card card-personaje">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-ilusionista" name="unl-ilusionista">
                    <label for="unl-ilusionista" class="checkbox-label"></label>
                </div>
                <span class="card-icon">🔮</span>
                <h2>La Ilusionista</h2>
                <p>Clase: Soporte | Rareza: Épica</p>
                <p class="unlock-req">Requisito: Completar el Códice de la Velocidad.</p>
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