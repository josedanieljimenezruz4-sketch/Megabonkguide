@extends('layouts.admin')

@section('title', 'Panel de Administración | MEGABONK GUIDE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-dashboard">
        <h1 class="admin-title">👑 Dashboard</h1>

        <!-- Tarjetas de estadísticas globales del sistema -->
        <div class="admin-stats-grid">
            <a href="{{ route('admin.users.index') }}" class="admin-stat-card" style="border-left-color: #00d2ff;">
                <h3>👥 Usuarios Totales</h3>
                <p class="stat-value">{{ $totalUsuarios }}</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-stat-card" style="border-left-color: #ff4b2b;">
                <h3>👑 Administradores</h3>
                <p class="stat-value">{{ $totalAdmins }}</p>
            </a>
            <a href="{{ route('admin.catalogo.index') }}" class="admin-stat-card" style="border-left-color: #ffcf00;">
                <h3>⚔️ Ítems Registrados</h3>
                <p class="stat-value">{{ $totalElementos }}</p>
            </a>
            <div class="admin-stat-card" style="border-left-color: #00e676;">
                <h3>🔓 Unlocks Realizados</h3>
                <p class="stat-value">{{ $totalDesbloqueos }}</p>
            </div>
            <a href="{{ route('admin.moderation.index') }}" class="admin-stat-card" style="border-left-color: #B965F0;">
                <h3>💬 Posts Comunidad</h3>
                <p class="stat-value">{{ $totalPosts }}</p>
            </a>
            <a href="{{ route('admin.moderation.index') }}" class="admin-stat-card" style="border-left-color: #36d1dc;">
                <h3>⚒️ Builds Totales</h3>
                <p class="stat-value">{{ $totalBuilds }}</p>
            </a>
        </div>

        <!-- Tabla de los últimos 10 usuarios registrados -->
        <h2>Últimos Usuarios</h2>
        <table class="admin-users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimosUsuarios as $usuario)
                <tr>
                    <td>#{{ $usuario->id }}</td>
                    <td>{{ $usuario->username }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        @if($usuario->is_admin)
                            <span class="badge-admin">Admin</span>
                        @else
                            <span class="badge-user">Usuario</span>
                        @endif
                    </td>
                    <td>{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Herramientas de Mantenimiento --}}
        <h2 class="admin-maintenance-title">🔧 Herramientas de Mantenimiento</h2>
        @if(session('success'))
            <div class="admin-success-alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="admin-maintenance-card">
            <div>
                <h3>🗑️ Purgar Registros Huérfanos</h3>
                <p>Elimina registros de <code>user_unlocks</code> cuyo ítem ya no existe en el catálogo.</p>
            </div>
            <form action="{{ route('admin.system.purgeOrphans') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas purgar los registros huérfanos? Esta acción es irreversible.');">
                @csrf
                <button type="submit" class="btn-purge">
                    🧹 Ejecutar Purga
                </button>
            </form>
        </div>
    </div>
@endsection
