@extends('layouts.app')

@section('title', 'Tier Lists de la Comunidad | MEGABONK GUIDE')

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
            color: #36d1dc;
            margin-bottom: 30px;
            font-size: 2rem;
            text-transform: uppercase;
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
        .btn-delete {
            background-color: #ff4757;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-delete:hover {
            background-color: #ff6b81;
        }
        .alert-success {
            background-color: #2ecc71;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            margin-top: 20px;
            padding: 0;
            gap: 10px;
        }
        .pagination .page-item .page-link {
            padding: 8px 12px;
            background: #2c2f33;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination .page-item.active .page-link {
            background: #ff416c;
            border-color: #ff416c;
        }
    </style>
@endpush

@section('content')
    <div class="admin-dashboard">
        <h1 class="admin-title">📋 Tier Lists de la Comunidad</h1>
        
        <div style="margin-bottom: 20px;">
             <a href="{{ route('admin.dashboard') }}" style="color: #36d1dc; text-decoration: none; font-weight: bold;">&larr; Volver al Panel Principal</a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="admin-users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tierLists as $tl)
                <tr>
                    <td style="font-weight: bold; color: #ffcf00;">#{{ $tl->id }}</td>
                    <td>
                        <a href="{{ route('community-tierlists.show', $tl->id) }}" style="color: white; text-decoration: none; font-weight: bold;" target="_blank">
                            {{ Str::limit($tl->titulo, 40) }}
                        </a>
                    </td>
                    <td>{{ $tl->user->username ?? 'Anónimo' }}</td>
                    <td><span style="background: rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 12px; font-size: 0.8em; text-transform: uppercase;">{{ $tl->categoria }}</span></td>
                    <td style="color: #aaa; font-size: 0.9em;">{{ $tl->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.community-tierlists.destroy', $tl->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar permanentemente la Tier List: {{ addslashes($tl->titulo) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar Tier List">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay Tier Lists de la comunidad.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div style="margin-top: 30px;" class="d-flex justify-content-center">
            @if(method_exists($tierLists, 'links'))
                {{ $tierLists->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>
@endsection
