<header class="main-header">
    <div class="header-content">
        <a href="{{ route('home') }}" class="site-title enlace-logo">
            <img src="{{ asset('images/iconotlabaho.webp') }}" alt="Logo MEGABONK GUIDE" class="imagen-logo">
            <span class="texto-logo">MEGABONK GUIDE</span>
        </a>

        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('tierlist') }}"
                        class="{{ request()->routeIs('tierlist*') ? 'active-link' : '' }}">TIERLIST</a></li>
                <li><a href="{{ route('builds.index') }}"
                        class="{{ request()->routeIs('builds.*') ? 'active-link' : '' }}">BUILDS</a></li>
                <li><a href="{{ route('comunity.index') }}"
                        class="{{ request()->routeIs('comunity.*') ? 'active-link' : '' }}">COMMUNITY</a></li>
                <li class="dropdown">
                    <a href="{{ route('unlocks.index') }}"
                        class="{{ request()->routeIs('unlocks.*') ? 'active-link' : '' }}">UNLOCKS <span
                            class="arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('unlocks.weapons') }}">Armas</a></li>
                        <li><a href="{{ route('unlocks.tomes') }}">Tomos</a></li>
                        <li><a href="{{ route('unlocks.items') }}">Items</a></li>
                        <li><a href="{{ route('unlocks.characters') }}">Personajes</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('meta') }}"
                        class="{{ request()->routeIs('meta*') ? 'active-link' : '' }}">META</a></li>
                <li><a href="{{ route('wiki.index') }}"
                        class="{{ request()->routeIs('wiki.*') ? 'active-link' : '' }}">INFO GENERAL</a></li>
                <li><a href="{{ route('info.news') }}"
                        class="{{ request()->routeIs('info.news') ? 'active-link' : '' }}">NOVEDADES</a></li>
                <li><a href="{{ route('leaderboard') }}"
                        class="{{ request()->routeIs('leaderboard*') ? 'active-link' : '' }}">LEADERBOARD</a></li>
            </ul>
        </nav>
        <div class="user-auth-links">
            @auth
                <!-- Si el usuario está logueado -->
                <div class="dropdown user-dropdown">
                    <div class="profile-header enlace-perfil-header">
                        <a href="{{ route('profile') }}" title="Ir a mi perfil" class="enlace-perfil-header">
                            <x-user-avatar :user="Auth::user()" size="40" class="profile-avatar" />
                            <span class="nombre-usuario-header">{{ Auth::user()->username ?? Auth::user()->name }}</span>
                        </a>
                        <span class="dropdown-arrow flecha-desplegable" onclick="toggleUserDropdown(event)">▼</span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-right" id="userDropdownMenu">
                        @if(Auth::user()->is_admin)
                            <li><a href="{{ route('admin.dashboard') }}" class="enlace-admin">👑 Panel Admin</a></li>
                            <li class="separator"></li>
                        @endif
                        <li><a href="{{ route('profile') }}">Mi Perfil</a></li>
                        <li><a href="{{ route('inventory') }}">Mi Inventario</a></li>
                        <li><a href="{{ route('profile.settings') }}">Cambiar Datos</a></li>
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
    // Alterna la visibilidad del menú desplegable del usuario
    function toggleUserDropdown(event) {
        event.stopPropagation();
        const dropdown = event.currentTarget.closest('.user-dropdown');
        dropdown.classList.toggle('active');
    }

    // Cierra el desplegable al hacer clic fuera de él
    window.addEventListener('click', function (event) {
        const dropdowns = document.querySelectorAll('.user-dropdown');
        dropdowns.forEach(function (dropdown) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>

<!-- Toast Global de Notificaciones -->
<div id="global-toast" class="notificacion-toast">Progreso actualizado</div>
<script>
    // Muestra una notificación toast temporal con el mensaje indicado
    window.showToast = function (message) {
        const toast = document.getElementById("global-toast");
        if (message) toast.innerText = message;
        toast.classList.add("show");
        setTimeout(function () {
            toast.classList.remove("show");
        }, 2000);
    }
</script>