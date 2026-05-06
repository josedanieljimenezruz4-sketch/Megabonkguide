@extends('layouts.admin')

@section('title', 'Panel de Administración | MEGABONK GUIDE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-dashboard">
        <h1 class="admin-title">👑 Dashboard</h1>

        <div class="admin-stats-grid">
            <div class="admin-stat-card" style="border-left-color: #00d2ff;">
                <h3>👥 Usuarios Totales</h3>
                <p class="stat-value">{{ $totalUsuarios }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #ff4b2b;">
                <h3>👑 Administradores</h3>
                <p class="stat-value">{{ $totalAdmins }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #ffcf00;">
                <h3>⚔️ Ítems Registrados</h3>
                <p class="stat-value">{{ $totalElementos }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #00e676;">
                <h3>🔓 Unlocks Realizados</h3>
                <p class="stat-value">{{ $totalDesbloqueos }}</p>
            </div>
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
    </div>
@endsection
