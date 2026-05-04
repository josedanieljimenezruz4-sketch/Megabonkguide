<header class="main-header">
    <div class="header-content">
        <a href="{{ route('home') }}" class="site-title">
            <img src="iconotlabaho.webp" alt="Logo MEGABONK GUIDE" id="header-logo">
            <a href="{{ route('home') }}" class="site-title">MEGABONK GUIDE</a>
        </a>

        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                <li><a href="{{ route('builds.index') }}">BUILDS</a></li>
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
                <li><a href="{{ route('wiki.index') }}">INFO GENERAL</a></li>
                <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                <li><a href="{{ route('leaderboard') }}">LEADERBOARD</a></li>
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