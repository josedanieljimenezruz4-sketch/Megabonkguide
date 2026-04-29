@extends('layouts.app')

@section('title', 'Panel de Administración | MEGABONK GUIDE')

@push('styles')
    <style>
        .admin-dashboard {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #1e1e24; /* Tono oscuro alineado al sitio */
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            color: #fff;
        }
        .admin-title {
            text-align: center;
            color: #ff4b2b;
            margin-bottom: 30px;
            font-size: 2rem;
            text-transform: uppercase;
        }
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .admin-stat-card {
            background-color: #2c2f33;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #ff416c;
        }
        .admin-stat-card h3 {
            margin: 0;
            color: #aaa;
            font-size: 1rem;
        }
        .admin-stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0 0;
            color: #fff;
        }
        .admin-users-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2f33;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
        }
        .admin-users-table th, .admin-users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #3f4247;
        }
        .admin-users-table th {
            background-color: #1a1a20;
            color: #ff416c;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .badge-admin {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .badge-user {
            background-color: #555;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="admin-dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 class="admin-title" style="margin-bottom: 0;">👑 Panel de Control</h1>
            <div>
                <a href="{{ route('admin.tierlist-manager') }}" style="background: linear-gradient(90deg, #B965F0, #8E44AD); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-right: 15px; box-shadow: 0 4px 10px rgba(185, 101, 240, 0.4); display: inline-block; margin-bottom: 10px;">
                    👁️ Panóptico Tier List
                </a>
                <a href="{{ route('admin.community-tierlists.index') }}" style="background: linear-gradient(90deg, #f1c40f, #e67e22); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-right: 15px; box-shadow: 0 4px 10px rgba(241, 196, 15, 0.4); display: inline-block; margin-bottom: 10px;">
                    📋 Tier Lists Comunidad
                </a>
                <a href="{{ route('admin.votes.index') }}" style="background: linear-gradient(90deg, #36d1dc, #5b86e5); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-right: 15px; box-shadow: 0 4px 10px rgba(91, 134, 229, 0.4); display: inline-block; margin-bottom: 10px;">
                    📊 Gestión de Votos
                </a>
                <a href="{{ route('admin.items.create') }}" style="background: linear-gradient(90deg, #ff4b2b, #ff416c); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 10px rgba(255, 65, 108, 0.4); display: inline-block; margin-bottom: 10px; margin-right: 15px;">
                    ➕ Añadir Ítem
                </a>
                <a href="{{ route('admin.wiki.index') }}" style="background: linear-gradient(90deg, #00C9FF, #92FE9D); color: #111; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 201, 255, 0.4); display: inline-block; margin-bottom: 10px;">
                    📚 Gestión Wiki
                </a>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card" style="border-left-color: #00d2ff;">
                <h3>👥 Usuarios Totales</h3>
                <p class="stat-value">{{ $totalUsers }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #ff4b2b;">
                <h3>👑 Administradores</h3>
                <p class="stat-value">{{ $totalAdmins }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #ffcf00;">
                <h3>⚔️ Ítems Registrados</h3>
                <p class="stat-value">{{ $totalItems }}</p>
            </div>
            <div class="admin-stat-card" style="border-left-color: #00e676;">
                <h3>🔓 Unlocks Realizados</h3>
                <p class="stat-value">{{ $totalUnlocks }}</p>
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
                @foreach($latestUsers as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge-admin">Admin</span>
                        @else
                            <span class="badge-user">Usuario</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
