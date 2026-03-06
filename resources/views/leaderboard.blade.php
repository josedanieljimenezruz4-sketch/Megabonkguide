<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard | Clasificación | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-leaderboard">

        <h1 class="page-title">🏆 Leaderboard Global: Bonk +10</h1>

        <div class="leaderboard-controls">
            <p>Clasificación basada en la puntuación más alta en el nivel de dificultad **Bonk +10** (Extremo).</p>
            <div class="filter-options">
                <label for="filter-difficulty">Filtrar Dificultad:</label>
                <select id="filter-difficulty" class="custom-select">
                    <option value="bonk10">Bonk +10 (Actual)</option>
                    <option value="bonk8">Bonk +8</option>
                    <option value="bonk5">Bonk +5</option>
                </select>
                <label for="filter-character">Filtrar Personaje:</label>
                <select id="filter-character" class="custom-select">
                    <option value="all">Todos</option>
                    <option value="maestra-bonk">La Maestra del Bonk</option>
                    <option value="berserker">El Berserker</option>
                </select>
            </div>
        </div>

        <div class="leaderboard-table-container">
            <table>
                <thead>
                    <tr>
                        <th class="rank-col">#</th>
                        <th>Jugador</th>
                        <th>Puntuación Final</th>
                        <th>Tiempo (min)</th>
                        <th>Personaje</th>
                        <th>Build (Link)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="top-3">
                        <td class="rank-col rank-gold">1</td>
                        <td>**BonkGod_X**</td>
                        <td class="score-highlight">9,875,120</td>
                        <td>42:15</td>
                        <td>Maestra del Bonk</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr class="top-3">
                        <td class="rank-col rank-silver">2</td>
                        <td>UltraKiller_99</td>
                        <td class="score-highlight">9,701,450</td>
                        <td>45:03</td>
                        <td>Berserker</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr class="top-3">
                        <td class="rank-col rank-bronze">3</td>
                        <td>MetaMaster</td>
                        <td class="score-highlight">8,980,000</td>
                        <td>50:11</td>
                        <td>Maestra del Bonk</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">4</td>
                        <td>xX_ShadowB_Xx</td>
                        <td>7,520,300</td>
                        <td>39:40</td>
                        <td>Ilusionista</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">5</td>
                        <td>DPS_Hero</td>
                        <td>7,489,120</td>
                        <td>48:22</td>
                        <td>Maestra del Bonk</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">6</td>
                        <td>Player006</td>
                        <td>6,500,000</td>
                        <td>55:00</td>
                        <td>Berserker</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">7</td>
                        <td>Prodigy</td>
                        <td>6,350,200</td>
                        <td>58:00</td>
                        <td>Maestra del Bonk</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">8</td>
                        <td>Gamer_One</td>
                        <td>6,100,000</td>
                        <td>60:00</td>
                        <td>Ilusionista</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">9</td>
                        <td>TheLegend</td>
                        <td>5,900,000</td>
                        <td>65:00</td>
                        <td>Berserker</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                    <tr>
                        <td class="rank-col">10</td>
                        <td>NoobMaster</td>
                        <td>5,500,000</td>
                        <td>70:00</td>
                        <td>Maestra del Bonk</td>
                        <td><a href="#" class="build-link">Ver Build</a></td>
                    </tr>
                </tbody>
            </table>
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