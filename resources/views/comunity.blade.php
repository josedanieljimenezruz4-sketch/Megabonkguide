<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comunity.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-community">

        <h1 class="page-title">🗣️ Portal de la Comunidad</h1>

        <p class="intro-text-community">
            Comparte tus builds, estrategias, ideas y memes con el resto de la comunidad Bonker. ¡Sé respetuoso y
            contribuye a la guía!
        </p>

        <section class="community-actions">
            <a href="#" class="btn-create-post">✍️ Publicar Nuevo Contenido</a>

            <div class="filter-controls">
                <label for="category-filter">Filtrar por:</label>
                <select id="category-filter" class="custom-select">
                    <option value="recent">Más Reciente</option>
                    <option value="popular">Más Popular</option>
                    <option value="builds">Builds</option>
                    <option value="meta">Meta & Estrategia</option>
                    <option value="preguntas">Preguntas</option>
                </select>
            </div>
        </section>

        <section class="posts-list">

            <div class="post-card">
                <div class="post-header">
                    <span class="post-category tag-build">BUILD</span>
                    <h3>Mi Build de 100% Lifesteal con el Berserker</h3>
                    <span class="post-meta">Publicado por: **BonkLord** hace 3 horas</span>
                </div>
                <p class="post-summary">
                    He encontrado una combinación de Item Único y Tomos que permite curar todo el daño que recibes.
                    Funciona muy bien en Bonk +8, ¡deja un comentario si lo pruebas!
                </p>
                <div class="post-footer">
                    <span class="stats likes">👍 154</span>
                    <span class="stats comments">💬 32 Comentarios</span>
                    <a href="#" class="view-post-link">Ver Discusión →</a>
                </div>
            </div>

            <div class="post-card">
                <div class="post-header">
                    <span class="post-category tag-meta">META</span>
                    <h3>¿Deberían nerfear el Anillo Crítico en el 3.2?</h3>
                    <span class="post-meta">Publicado por: **MetaSlave** hace 1 día</span>
                </div>
                <p class="post-summary">
                    Desde el último parche, el escalado de crítico parece demasiado alto. Esto está limitando la
                    diversidad de builds. ¿Qué opina la comunidad?
                </p>
                <div class="post-footer">
                    <span class="stats likes">👍 89</span>
                    <span class="stats comments">💬 68 Comentarios</span>
                    <a href="#" class="view-post-link">Ver Discusión →</a>
                </div>

            </div>

            <div class="post-card">
                <div class="post-header">
                    <span class="post-category tag-question">PREGUNTA</span>
                    <h3>¿Dónde encuentro el Tomo del Poder?</h3>
                    <span class="post-meta">Publicado por: **NewBonker** hace 5 días</span>
                </div>
                <p class="post-summary">
                    He estado buscando por todos los mapas y no logro encontrarlo. ¿Alguien tiene alguna pista sobre
                    dónde se *dropea*?
                </p>
                <div class="post-footer">
                    <span class="stats likes">👍 12</span>
                    <span class="stats comments">💬 9 Comentarios</span>
                    <a href="#" class="view-post-link">Ver Discusión →</a>
                </div>
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
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>

</body>

</html>