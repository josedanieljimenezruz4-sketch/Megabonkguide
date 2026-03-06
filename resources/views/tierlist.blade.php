<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier List | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-tierlist">

        <h1 class="page-title">Tier List: Ranking de la Meta Actual</h1>

        <p class="intro-text-tierlist">
            Nuestra Tier List se actualiza semanalmente con base en el rendimiento de los objetos y personajes en el
            **Leaderboard**.
            Las unidades se clasifican según su potencial máximo en los desafíos de mayor dificultad (Bonk +10).
            ¡Recuerda que la habilidad del jugador siempre es el factor más importante!
        </p>

        <div class="tierlist-container">
            <table>
                <thead>
                    <tr>
                        <th class="tier-rank">RANGO</th>
                        <th>UNIDADES DESTACADAS</th>
                        <th>DESCRIPCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tier-s">
                        <td>S</td>
                        <td>Personaje X, Arma Y (Hacha), Tomo Z (Velocidad)</td>
                        <td>Dominantes en el meta. Obligatorios para récords de puntuación alta y desafíos extremos.
                        </td>
                    </tr>
                    <tr class="tier-a">
                        <td>A</td>
                        <td>Personaje A, Item B (Anillo), Arma C</td>
                        <td>Excelentes y versátiles. Pueden limpiar el contenido más difícil, pero requieren una
                            sinergia de build específica.</td>
                    </tr>
                    <tr class="tier-b">
                        <td>B</td>
                        <td>Personaje D, Tomo E, Item F</td>
                        <td>Viables y funcionales. Buenos picks para la mayoría del contenido, pero se quedan atrás en
                            los desafíos de nivel superior.</td>
                    </tr>
                    <tr class="tier-c">
                        <td>C</td>
                        <td>Personaje G, Arma H</td>
                        <td>Nicho o débiles. Solo útiles en composiciones de equipo muy específicas o para
                            principiantes.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <section class="meta-links">
            <h2>🔗 Analiza la Meta</h2>
            <p>Consulta las **Builds** de mayor rango o el **Leaderboard** para ver estas unidades en acción.</p>
            <div class="action-buttons-small">
                <a href="{{ route('builds.search') }}" class="btn btn-primary-link">🔎 Buscador de Builds</a>
                <a href="{{ route('leaderboard') }}" class="btn btn-secondary-link">🏆 Ver Leaderboard</a>
            </div>
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