<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
    <style>
        .progress-bar-container {
            width: 100%;
            background-color: #2c2f33; /* Fondo oscuro sutil */
            border-radius: 8px;
            height: 12px;
            margin-top: 15px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff4b2b, #ff416c); /* Gradiente llamativo */
            transition: width 0.8s ease-in-out;
            border-radius: 8px;
        }
        .progress-text {
            font-size: 0.85rem;
            color: #b9bbbe;
            margin-top: 5px;
            text-align: right;
            font-weight: 600;
        }
        .stat-unlocks .stat-value {
            font-size: 1.1rem; /* Ajustado para que el texto encaje mejor */
            font-weight: bold;
        }
        .mt-3 {
            margin-top: 15px;
            display: inline-block;
        }
    </style>
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
                <p class="stat-value">Has desbloqueado {{ $unlockedItems }} de {{ $totalItems }} ítems</p>
                
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $percentage }}%;"></div>
                </div>
                <p class="progress-text">{{ $percentage }}% Completado</p>

                <a href="{{ route('unlocks.index') }}" class="card-link mt-3">Ver Requisitos →</a>
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
                <a href="{{ route('inventory') }}" class="btn-action" style="background: linear-gradient(90deg, #ff4b2b, #ff416c); color:white; font-weight:bold; border:none; box-shadow: 0 4px 15px rgba(255, 65, 108, 0.4);">🎒 Abrir Mi Inventario</a>
                <a href="{{ route('profile.settings') }}" class="btn-action btn-manage-data">⚙️ Configuración</a>
                <a href="#user-builds" class="btn-action btn-my-builds">🛠️ Mis Builds Guardadas</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline; width: 100%;">
                    @csrf
                    <button type="submit" class="btn-action btn-logout" style="width:100%; border:none; cursor:pointer; font-size:1rem;">🚪 Cerrar Sesión</button>
                </form>
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
