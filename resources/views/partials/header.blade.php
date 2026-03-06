<header class="main-header">
    <div class="header-content">
        <a href="{{ route('home') }}" class="site-title">
            <img src="iconotlabaho.webp" alt="Logo MEGABONK GUIDE" id="header-logo">
            <a href="{{ route('home') }}" class="site-title">MEGABONK GUIDE</a>
        </a>

        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                <li><a href="{{ route('builds.search') }}">BUSCADOR DE BUILDS</a></li>
                <li><a href="{{ route('comunity.index') }}">COMMUNITY</a></li>
                <li class="dropdown">
                    <a href="{{ route('unlocks.index') }}">UNLOCKS ▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('unlocks.weapons') }}">Armas</a></li>
                        <li><a href="{{ route('unlocks.tomes') }}">Tomos</a></li>
                        <li><a href="{{ route('unlocks.items') }}">Items</a></li>
                        <li><a href="{{ route('unlocks.characters') }}">Personajes</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('meta') }}">META</a></li>
                <li><a href="{{ route('info.general') }}">INFO GENERAL</a></li>
                <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                <li><a href="{{ route('leaderboard') }}">LEADERBOARD</a></li>
            </ul>
        </nav>
        <div class="user-auth-links">
            @auth
                <!-- Si el usuario está logueado -->
                <div class="dropdown user-dropdown">
                    <div class="profile-header" onclick="toggleUserDropdown(event)">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=B965F0&color=fff&size=64"
                            alt="{{ Auth::user()->name }}" class="profile-avatar">
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-right" id="userDropdownMenu">
                        <li><a href="#">Configuración</a></li>
                        <li><a href="#">Editar perfil</a></li>
                        <li class="separator"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item-btn">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <!-- Si el usuario NO está logueado -->
                <a href="{{ route('login') }}" class="auth-link">Login</a> |
                <a href="{{ route('register') }}" class="auth-link">Registro</a>
            @endauth
        </div>
    </div>
</header>

<script>
    function toggleUserDropdown(event) {
        event.stopPropagation(); // Prevent immediate closing
        const dropdown = event.currentTarget.closest('.user-dropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    window.addEventListener('click', function (event) {
        const dropdowns = document.querySelectorAll('.user-dropdown');
        dropdowns.forEach(function (dropdown) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>