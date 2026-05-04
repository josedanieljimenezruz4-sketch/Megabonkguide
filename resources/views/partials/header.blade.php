<header class="main-header">
    <div class="header-content">
        <a href="{{ route('home') }}" class="site-title" style="display: flex; align-items: center; gap: 10px; text-decoration: none; white-space: nowrap;">
            <img src="{{ asset('images/iconotlabaho.webp') }}" alt="Logo MEGABONK GUIDE" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <span style="font-size: 1.4rem; font-weight: bold; letter-spacing: 1px;">MEGABONK GUIDE</span>
        </a>

        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('tierlist') }}" class="{{ request()->routeIs('tierlist*') ? 'active-link' : '' }}">TIERLIST</a></li>
                <li><a href="{{ route('builds.index') }}" class="{{ request()->routeIs('builds.*') ? 'active-link' : '' }}">BUILDS</a></li>
                <li><a href="{{ route('comunity.index') }}" class="{{ request()->routeIs('comunity.*') ? 'active-link' : '' }}">COMMUNITY</a></li>
                <li class="dropdown">
                    <a href="{{ route('unlocks.index') }}" class="{{ request()->routeIs('unlocks.*') ? 'active-link' : '' }}">UNLOCKS <span class="arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('unlocks.weapons') }}">Armas</a></li>
                        <li><a href="{{ route('unlocks.tomes') }}">Tomos</a></li>
                        <li><a href="{{ route('unlocks.items') }}">Items</a></li>
                        <li><a href="{{ route('unlocks.characters') }}">Personajes</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('meta') }}" class="{{ request()->routeIs('meta*') ? 'active-link' : '' }}">META</a></li>
                <li><a href="{{ route('wiki.index') }}" class="{{ request()->routeIs('wiki.*') ? 'active-link' : '' }}">INFO GENERAL</a></li>
                <li><a href="{{ route('info.news') }}" class="{{ request()->routeIs('info.news') ? 'active-link' : '' }}">NOVEDADES</a></li>
                <li><a href="{{ route('leaderboard') }}" class="{{ request()->routeIs('leaderboard*') ? 'active-link' : '' }}">LEADERBOARD</a></li>
            </ul>
        </nav>
        <div class="user-auth-links">
            @auth
                <!-- Si el usuario está logueado -->
                <div class="dropdown user-dropdown">
                    <div class="profile-header" style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ route('profile') }}" title="Ir a mi perfil" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                            <x-user-avatar :user="Auth::user()" size="40" class="profile-avatar" />
                            <span style="color: #36d1dc; font-weight: bold; text-shadow: 0 0 8px rgba(54, 209, 220, 0.6); font-size: 1rem; text-transform: uppercase;">{{ Auth::user()->username ?? Auth::user()->name }}</span>
                        </a>
                        <span class="dropdown-arrow" onclick="toggleUserDropdown(event)" style="cursor: pointer; margin-left: 4px; padding: 5px;">▼</span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-right" id="userDropdownMenu">
                        @if(Auth::user()->is_admin)
                            <li><a href="{{ route('admin.dashboard') }}" style="color: #ff416c; font-weight: bold;">👑 Panel Admin</a></li>
                            <li class="separator"></li>
                        @endif
                        <li><a href="{{ route('profile') }}">Mi Perfil</a></li>
                        <li><a href="{{ route('inventory') }}">🎒 Mi Inventario</a></li>
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

<!-- Toast Global UI -->
<div id="global-toast" class="toast-notification">Progreso actualizado</div>
<style>
.toast-notification {
    visibility: hidden;
    min-width: 250px;
    background: linear-gradient(90deg, #ff4b2b, #ff416c);
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 9999;
    right: 30px;
    bottom: 30px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(255, 65, 108, 0.4);
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.toast-notification.show {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
}
</style>
<script>
window.showToast = function(message) {
    const toast = document.getElementById("global-toast");
    if(message) toast.innerText = message;
    toast.classList.add("show");
    setTimeout(function(){ 
        toast.classList.remove("show"); 
    }, 2000);
}
</script>