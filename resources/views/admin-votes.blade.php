@extends('layouts.admin')

@section('title', 'Gestión de Votos | MEGABONK GUIDE')

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
        .btn-reset {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-reset:hover {
            background-color: #c0392b;
        }
        .btn-reset-all {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(255, 65, 108, 0.4);
            transition: 0.3s;
        }
        .btn-reset-all:hover {
            opacity: 0.9;
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="admin-title" style="margin-bottom: 0;">📊 Votos de Popularidad</h1>
            
            <form action="{{ route('admin.votes.resetAll') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres BORRAR TODOS los votos de TODOS los ítems? Esta acción no se puede deshacer.')">
                @csrf
                <button type="submit" class="btn-reset-all">
                    ⚠️ Reiniciar Popularidad Global
                </button>
            </form>
        </div>
        
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
                    <th>Ítem</th>
                    <th>Tipo</th>
                    <th>Votos Totales</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="display: flex; align-items: center; gap: 10px;">
                        @if($item->image_path)
                            <img src="{{ asset('images/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 30px; height: 30px; border-radius: 5px; object-fit: contain; background: #fff;">
                        @endif
                        {{ $item->name }}
                    </td>
                    <td><span style="background: rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 12px; font-size: 0.8em; text-transform: uppercase;">{{ $item->type }}</span></td>
                    <td style="font-size: 1.2rem; font-weight: bold; color: #ffcf00;">{{ $item->votes }}</td>
                    <td>
                        <form action="{{ route('admin.votes.resetItem', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro de resetear los votos de {{ $item->name }} a 0?')">
                            @csrf
                            <button type="submit" class="btn-reset">Resetear a 0</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No hay ítems registrados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación de Laravel -->
        <div style="margin-top: 30px;" class="d-flex justify-content-center">
            @if(method_exists($items, 'links'))
                {{ $items->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>
@endsection
