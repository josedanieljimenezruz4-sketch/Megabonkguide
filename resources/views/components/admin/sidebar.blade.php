<nav class="admin-sidebar">
    <div class="sidebar-header">
        <h2>👑 ADMIN PANEL</h2>
    </div>
    @php
        $activeClass = 'text-blue-400 bg-blue-500/10 border-l-4 border-blue-500 shadow-[inset_4px_0_15px_rgba(59,130,246,0.1)] drop-shadow-[0_0_15px_rgba(59,130,246,0.3)] font-bold';
        $inactiveClass = 'text-gray-300 hover:text-white hover:bg-white/5 border-l-4 border-transparent';
        $baseClass = 'flex items-center gap-3 px-4 py-3 transition-all duration-300 w-full';
    @endphp
    <ul class="sidebar-menu flex flex-col mt-4">
        <li><a href="{{ route('admin.dashboard') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}"><i>🏠</i> Dashboard</a></li>
        <li><a href="{{ route('admin.tierlist-manager') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.tierlist-manager') ? $activeClass : $inactiveClass }}"><i>📋</i> Tier Lists Oficial</a></li>
        <li><a href="{{ route('admin.community-tierlists.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.community-tierlists.*') ? $activeClass : $inactiveClass }}"><i>👥</i> Tier Lists Comunidad</a></li>
        <li><a href="{{ route('admin.wiki.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.wiki.*') ? $activeClass : $inactiveClass }}"><i>📚</i> Gestión Wiki</a></li>
        <li><a href="{{ route('admin.leaderboard.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.leaderboard.*') ? $activeClass : $inactiveClass }}"><i>🏆</i> Leaderboard</a></li>
        <li><a href="{{ route('admin.votes.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.votes.*') ? $activeClass : $inactiveClass }}"><i>📊</i> Gestión de Votos</a></li>
        <li>
            <a href="{{ route('admin.suggestions.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.suggestions.*') ? $activeClass : $inactiveClass }} justify-between">
                <div class="flex items-center gap-3">
                    <i>📧</i> <span>Sugerencias</span>
                </div>
                @if(isset($unreadSuggestionsCount) && $unreadSuggestionsCount > 0)
                    <span class="neon-pulse-circle">{{ $unreadSuggestionsCount }}</span>
                @endif
            </a>
        </li>
        <li><a href="{{ route('admin.moderation.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.moderation.*') ? $activeClass : $inactiveClass }}"><i>🛡️</i> Moderación</a></li>
        <li><a href="{{ route('admin.users.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}"><i>👥</i> Gestión de Usuarios</a></li>
        <li><a href="{{ route('admin.meta.index') }}" class="{{ $baseClass }} {{ request()->routeIs('admin.meta.*') ? $activeClass : $inactiveClass }}"><i>⚙️</i> Gestión Meta</a></li>
        
        <li style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);" class="pt-4 mt-8">
            <a href="{{ route('home') }}" class="{{ $baseClass }} {{ $inactiveClass }}"><i>⬅️</i> Volver a la Web</a>
        </li>
    </ul>
</nav>

<style>
.neon-pulse-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background-color: #dc2626; /* bg-red-600 */
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(220, 38, 38, 0.8);
    animation: pulse-red 2s infinite;
}

@keyframes pulse-red {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
    }
}
</style>
