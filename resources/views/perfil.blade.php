<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-profile">

        <header class="profile-header">
            <h1 class="profile-name">¡Hola, BonkLord!</h1>
            <p class="member-info">Miembro desde Diciembre 2025 | Nivel Bonk: 7</p>
        </header>

        <section class="profile-stats-grid">

            <div class="stat-card stat-score">
                <span class="stat-icon">🌟</span>
                <h3>Puntuación Máxima (Global)</h3>
                <p class="stat-value">9,875,120</p>
                <a href="{{ route('leaderboard') }}" class="card-link">Ver en Leaderboard →</a>
            </div>

            <div class="stat-card stat-builds">
                <span class="stat-icon">🔨</span>
                <h3>Builds Publicadas</h3>
                <p class="stat-value">5</p>
                <a href="#user-builds" class="card-link">Administrar Builds →</a>
            </div>

            <div class="stat-card stat-unlocks">
                <span class="stat-icon">🔓</span>
                <h3>Unlocks Completados</h3>
                <p class="stat-value">75/120</p>
                <a href="{{ route('unlocks.index') }}" class="card-link">Ver Requisitos →</a>
            </div>

            <div class="stat-card stat-contributions">
                <span class="stat-icon">💬</span>
                <h3>Contribuciones en Comunidad</h3>
                <p class="stat-value">124</p>
                <a href="{{ route('comunity.index') }}" class="card-link">Ver Actividad →</a>
            </div>
        </section>

        <section class="profile-actions">
            <h2>Configuración y Gestión</h2>
            <div class="action-buttons-group">
                <a href="{{ route('profile.settings') }}" class="btn-action btn-manage-data">⚙️ Cambiar Datos de la
                    Cuenta</a>
                <a href="#user-builds" class="btn-action btn-my-builds">🛠️ Mis Builds Guardadas</a>
                <a href="#" class="btn-action btn-logout">🚪 Cerrar Sesión</a>
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