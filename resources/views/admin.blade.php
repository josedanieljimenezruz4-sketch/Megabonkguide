@extends('layouts.admin')

@section('title', 'Panel de Administración | MEGABONK GUIDE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-dashboard">
        <h1 class="admin-title">👑 Dashboard</h1>

        <div class="admin-stats-grid">
            <a href="{{ route('admin.users.index') }}" class="admin-stat-card" style="border-left-color: #00d2ff; text-decoration: none; color: inherit;">
                <h3>👥 Usuarios Totales</h3>
                <p class="stat-value">{{ $totalUsuarios }}</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-stat-card" style="border-left-color: #ff4b2b; text-decoration: none; color: inherit;">
                <h3>👑 Administradores</h3>
                <p class="stat-value">{{ $totalAdmins }}</p>
            </a>
            <a href="{{ route('admin.catalogo.index') }}" class="admin-stat-card" style="border-left-color: #ffcf00; text-decoration: none; color: inherit;">
                <h3>⚔️ Ítems Registrados</h3>
                <p class="stat-value">{{ $totalElementos }}</p>
            </a>
            <div class="admin-stat-card" style="border-left-color: #00e676;">
                <h3>🔓 Unlocks Realizados</h3>
                <p class="stat-value">{{ $totalDesbloqueos }}</p>
            </div>
            <a href="{{ route('admin.moderation.index') }}" class="admin-stat-card" style="border-left-color: #B965F0; text-decoration: none; color: inherit;">
                <h3>💬 Posts Comunidad</h3>
                <p class="stat-value">{{ $totalPosts }}</p>
            </a>
            <a href="{{ route('admin.moderation.index') }}" class="admin-stat-card" style="border-left-color: #36d1dc; text-decoration: none; color: inherit;">
                <h3>⚒️ Builds Totales</h3>
                <p class="stat-value">{{ $totalBuilds }}</p>
            </a>
        </div>

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
        <h2 style="margin-top: 2.5rem;">🔧 Herramientas de Mantenimiento</h2>
        @if(session('success'))
            <div style="background: rgba(34,197,94,0.15); border-left: 4px solid #22c55e; padding: 12px 16px; margin-bottom: 1.5rem; color: #fff; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0 0 6px 0; color: #f59e0b; font-size: 1.1rem;">🗑️ Purgar Registros Huérfanos</h3>
                <p style="margin: 0; color: #9ca3af; font-size: 0.9rem;">Elimina registros de <code style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">user_unlocks</code> cuyo ítem ya no existe en el catálogo.</p>
            </div>
            <form action="{{ route('admin.system.purgeOrphans') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas purgar los registros huérfanos? Esta acción es irreversible.');">
                @csrf
                <button type="submit" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #000; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 1px;">
                    🧹 Ejecutar Purga
                </button>
            </form>
        </div>
    </div>
@endsection
