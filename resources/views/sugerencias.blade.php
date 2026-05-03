<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerencias | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sugerencias.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-suggestions">

        <div class="suggestions-card">
            <h1 class="page-title">📧 Envíanos tu Sugerencia</h1>

            <p class="intro-text-suggestions">
                Tu opinión es vital para mejorar MEGABONK GUIDE. Utiliza este formulario para reportar errores, sugerir
                contenido o proponer nuevas funcionalidades.
            </p>

            <form class="suggestions-form" action="#" method="POST">

                <div class="form-group">
                    <label for="type">Tipo de Sugerencia:</label>
                    <select id="type" name="type" required>
                        <option value="error">Reporte de Error/Dato Incorrecto</option>
                        <option value="contenido">Sugerencia de Contenido (Meta, Builds)</option>
                        <option value="funcionalidad">Nueva Funcionalidad de la Web</option>
                        <option value="general">Comentario General</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Tu Nombre de Usuario (Opcional):</label>
                    <input type="text" id="name" name="name" placeholder="Ej: BonkLord">
                </div>

                <div class="form-group">
                    <label for="email">Tu Email (Para seguimiento, opcional):</label>
                    <input type="email" id="email" name="email" placeholder="email@ejemplo.com">
                </div>

                <div class="form-group">
                    <label for="message">Mensaje / Descripción detallada:</label>
                    <textarea id="message" name="message" rows="8"
                        placeholder="Explica tu sugerencia o el error encontrado aquí..." required></textarea>
                </div>

                <button type="submit" class="btn-submit-suggestion">Enviar Sugerencia</button>
            </form>
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