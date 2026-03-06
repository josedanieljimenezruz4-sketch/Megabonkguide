<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info General | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/info_general.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-info">

        <h1 class="page-title">📚 Información General y Ayuda</h1>

        <div class="tabs-container">

            <nav class="tabs-nav">
                <a href="#reglas" class="tab-link active">Reglas Básicas</a>
                <a href="#lore" class="tab-link">Lore (Trasfondo)</a>
                <a href="#faq" class="tab-link">Preguntas Frecuentes</a>
            </nav>

            <div class="tabs-content">

                <section id="reglas" class="tab-pane active">
                    <h2>Principios del Combate Bonker</h2>
                    <p class="section-intro">
                        MEGABONK se basa en la acumulación de poder y el control de masas. Dominar los siguientes
                        conceptos es esencial para el éxito:
                    </p>
                    <ul class="info-list">
                        <li>**El Bonk Crítico:** Cada 10 golpes estándar, tu ataque se convierte en un Bonk Crítico,
                            infligiendo daño masivo. La Meta se centra en reducir este contador.</li>
                        <li>**Acumulación (Stacking):** Los ítems y tomos se pueden acumular hasta 5 veces,
                            multiplicando sus efectos. El *soft cap* (límite suave) se alcanza al tercer *stack*.</li>
                        <li>**El Desafío Bonk+:** Al desbloquear el primer Tomo Legendario, se activan los niveles de
                            dificultad Bonk+, que escalan la vida del enemigo exponencialmente.</li>
                    </ul>
                </section>

                <section id="lore" class="tab-pane">
                    <h2>El Origen de la Tierra de MEGABONK</h2>
                    <p class="section-intro">
                        Hace eones, el mundo fue forjado por el Gran Arquitecto (ahora un Personaje jugable). Pero una
                        grieta en el Códice de la Velocidad liberó a las criaturas de las Sombras, obligando a los
                        héroes a dominar el arte del Bonk para restaurar el equilibrio.
                    </p>
                    <div class="lore-details">
                        <p><strong>El Códice:</strong> Contiene las reglas del tiempo y el espacio. Su corrupción es la
                            causa de todos los enemigos que enfrentas.</p>
                        <p><strong>Los Héroes:</strong> Cada personaje es un fragmento del poder olvidado del
                            Arquitecto, reencarnado para sellar la grieta.</p>
                    </div>
                </section>

                <section id="faq" class="tab-pane">
                    <h2>Preguntas Frecuentes (FAQ)</h2>

                    <details class="faq-item">
                        <summary>¿Cómo desbloqueo los Tomos Legendarios?</summary>
                        <p>Los Tomos Legendarios solo se *dropean* al completar desafíos específicos en el modo Bonk+3 o
                            superior. Consulta la sección de Unlocks para ver los requisitos exactos.</p>
                    </details>

                    <details class="faq-item">
                        <summary>¿Puedo jugar con amigos (Modo Cooperativo)?</summary>
                        <p>Actualmente, MEGABONK es un juego de un solo jugador. Sin embargo, el Leaderboard y la
                            Comunidad permiten la competencia amistosa y la publicación de builds.</p>
                    </details>

                    <details class="faq-item">
                        <summary>¿Cuándo es el próximo parche?</summary>
                        <p>Generalmente, cada 4-6 semanas. Revisa la sección de Novedades para las fechas y *patch
                            notes* oficiales.</p>
                    </details>
                </section>

            </div>
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