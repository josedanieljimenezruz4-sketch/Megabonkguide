<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Actual | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/meta.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-meta">

        <h1 class="page-title">🧠 Análisis de la Meta Actual (Parche 3.1)</h1>

        <p class="intro-text-meta">
            La Meta de MEGABONK se define por los estilos de juego más efectivos en los niveles de dificultad más altos
            (Bonk +8 y superior). Esta es nuestra evaluación actual, enfocada en daño sostenido y supervivencia extrema.
        </p>

        <div class="meta-section-container">

            <section class="meta-strategies">
                <h2>Estrategias Dominantes</h2>
                <div class="strategy-card">
                    <h3>1. El Bonk Perpetuo (DPS)</h3>
                    <p>Se basa en maximizar la Velocidad de Ataque y el *Lifesteal* (robo de vida) para mantener el daño
                        constante y regenerar vida al mismo tiempo. Requiere el Tomo de Velocidad y el Anillo Crítico.
                    </p>
                </div>
                <div class="strategy-card">
                    <h3>2. El Muro Golem (Tanque)</h3>
                    <p>Estrategia defensiva que utiliza al Berserker con ítems de generación de Escudo y la habilidad de
                        Congelación del Arma X. Ideal para equipos que requieren un *frontline* indestructible.</p>
                </div>
                <div class="strategy-card">
                    <h3>3. Soporte Fantasmal (Control)</h3>
                    <p>Menos popular, pero esencial para récords. Consiste en usar a la Ilusionista para controlar
                        grandes grupos de enemigos, permitiendo que el DPS se centre en los Jefes (Bosses).</p>
                </div>
            </section>

            <aside class="meta-sidebar">
                <h2>Cambios Clave del Parche 3.1</h2>
                <ul class="patch-notes">
                    <li class="buff">+ Mejora (Buff) al Hacha Púrpura Radiante: Su efecto de Bonk ahora se aplica a más
                        enemigos cercanos.</li>
                    <li class="nerf">- Debilitamiento (Nerf) a los Items de Curación: El *cooldown* (tiempo de recarga)
                        de todos los consumibles de salud ha aumentado en 15 segundos.</li>
                    <li class="new">⭐ Nuevo Personaje: "El Arquitecto" introducido, aún sin impacto significativo en la
                        Meta.</li>
                </ul>

                <h2>Personajes en la Cima</h2>
                <div class="top-characters">
                    <p>👑 **La Maestra del Bonk:** Sigue siendo la reina del DPS debido a su escalado crítico. (Ver <a
                            href="{{ route('unlocks.characters') }}">guía de Personajes</a>)</p>
                    <p>🛡️ **El Berserker:** Su reciente *buff* de daño base lo ha consolidado como el mejor Tanque
                        agresivo.</p>
                </div>

                <a href="{{ route('tierlist') }}" class="btn-tierlist-cta">Ver la Tier List Completa →</a>
            </aside>

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