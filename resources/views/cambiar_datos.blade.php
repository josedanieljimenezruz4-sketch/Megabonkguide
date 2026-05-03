<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Datos | Gestión de Cuenta | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-auth">

        <div class="auth-card">
            <h1 class="auth-title">Gestión de Cuenta</h1>
            <p class="auth-subtitle">Actualiza tu información personal y contraseña.</p>

            <form class="auth-form" action="#" method="POST">

                <h2>Datos Personales</h2>
                <div class="form-group">
                    <label for="username">Nombre de Usuario actual</label>
                    <input type="text" id="username" name="username" value="BonkLord" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="bonk_lord@email.com" required>
                </div>

                <h2 class="form-section-title">Cambiar Contraseña</h2>
                <div class="form-group">
                    <label for="current-password">Contraseña Actual</label>
                    <input type="password" id="current-password" name="current_password"
                        placeholder="Requerido para guardar cambios">
                </div>

                <div class="form-group">
                    <label for="new-password">Nueva Contraseña</label>
                    <input type="password" id="new-password" name="new_password"
                        placeholder="Dejar vacío si no deseas cambiarla">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-auth">Guardar Cambios</button>
                </div>
            </form>

            <div class="auth-footer-links">
                <a href="{{ route('profile') }}" class="btn-action btn-manage-data">← Volver al Perfil</a>
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