@extends('layouts.admin')

@section('title', 'Gestión de Usuarios | Admin')

@push('styles')
    <style>
        .admin-header { margin-bottom: 30px; }
        .admin-title {
            color: #ff416c; /* Rosa neón para usuarios */
            margin-bottom: 10px;
            font-size: 2rem;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(255, 65, 108, 0.4);
        }
        .admin-card {
            background-color: rgba(44, 47, 51, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td {
            padding: 15px; text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05); color: #eee;
        }
        .admin-table th { color: #ff416c; text-transform: uppercase; font-size: 0.9rem; background: rgba(0,0,0,0.3); }
        .admin-btn { padding: 8px 15px; border-radius: 6px; color: #fff; font-weight: bold; border: none; cursor: pointer; transition: all 0.3s; font-size: 0.85rem; }
        .btn-ban { background: linear-gradient(90deg, #f7b733, #fc4a1a); box-shadow: 0 4px 10px rgba(252, 74, 26, 0.4); }
        .btn-ban:hover { opacity: 0.9; }
        .btn-unban { background: linear-gradient(90deg, #a8ff78, #78ffd6); color: #000; box-shadow: 0 4px 10px rgba(168, 255, 120, 0.4); }
        .btn-unban:hover { opacity: 0.9; }
        .btn-delete { background: linear-gradient(90deg, #ff4b2b, #ff416c); box-shadow: 0 4px 10px rgba(255, 65, 108, 0.4); }
        .btn-delete:hover { opacity: 0.9; }
        
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.8); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(5px);
        }
        .modal-content {
            background: #1e1e24; border: 1px solid rgba(255, 65, 108, 0.3); box-shadow: 0 0 20px rgba(255, 65, 108, 0.2);
            border-radius: 12px; width: 90%; max-width: 500px; padding: 30px; position: relative; color: white;
        }
        .modal-close { position: absolute; top: 15px; right: 15px; background: none; border: none; color: #ff4757; font-size: 1.5rem; cursor: pointer; }
        
        .banned-status { color: #f7b733; font-weight: bold; }
        .active-status { color: #a8ff78; }

        select.ban-select {
            background: rgba(15, 15, 15, 0.8);
            border: 1px solid rgba(255,255,255,0.1);
            color: white; padding: 8px; border-radius: 6px; outline: none; margin-bottom: 15px; width: 100%;
        }
        .ban-select option { background-color: #121212; color: #fff; }
    </style>
@endpush

@section('content')
<div class="admin-header">
    <h1 class="admin-title">👥 Gestión de Usuarios</h1>
    <p>Administra, suspende o elimina cuentas de la plataforma.</p>
</div>

@if(session('success'))
    <div style="background: rgba(168, 255, 120, 0.2); border-left: 4px solid #a8ff78; padding: 15px; margin-bottom: 20px; color: #fff; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="{{ $user->avatar_url }}" alt="avatar" style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
                            <strong>{{ $user->username ?? $user->name }}</strong>
                            @if($user->is_admin) <span style="background: #a8ff78; color: #000; font-size: 0.7em; padding: 2px 6px; border-radius: 4px; font-weight: bold;">ADMIN</span> @endif
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->banned_until && \Carbon\Carbon::parse($user->banned_until)->isFuture())
                            <span class="banned-status">Suspendido hasta {{ \Carbon\Carbon::parse($user->banned_until)->format('d/m/Y') }}</span>
                        @else
                            <span class="active-status">Activo</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px;">
                            @if($user->banned_until && \Carbon\Carbon::parse($user->banned_until)->isFuture())
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="duration" value="unban">
                                    <button type="submit" class="admin-btn btn-unban">Desbanear</button>
                                </form>
                            @else
                                <button type="button" class="admin-btn btn-ban" onclick="openBanModal({{ $user->id }}, '{{ addslashes($user->username ?? $user->name) }}')">Suspender</button>
                            @endif

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿SEGURO? Esta acción es IRREVERSIBLE y borrará todo el contenido de este usuario.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

<!-- Ban Modal -->
<div id="banModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeBanModal()">✖</button>
        <h2 style="color: #ff416c; margin-top:0;">Suspender Usuario</h2>
        <p>¿Por cuánto tiempo deseas suspender a <strong id="ban-user-name"></strong>?</p>
        
        <form id="banForm" method="POST">
            @csrf
            <select name="duration" class="ban-select" required>
                <option value="24">24 Horas</option>
                <option value="72">3 Días</option>
                <option value="168">7 Días</option>
                <option value="720">1 Mes</option>
                <option value="permanent">Permanente</option>
            </select>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="admin-btn" style="background: #555;" onclick="closeBanModal()">Cancelar</button>
                <button type="submit" class="admin-btn btn-ban">Aplicar Suspensión</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openBanModal(id, username) {
        document.getElementById('ban-user-name').textContent = username;
        document.getElementById('banForm').action = '/admin/users/' + id + '/ban';
        document.getElementById('banModal').style.display = 'flex';
    }

    function closeBanModal() {
        document.getElementById('banModal').style.display = 'none';
    }

    document.getElementById('banModal').addEventListener('click', function(e) {
        if (e.target === this) closeBanModal();
    });
</script>
@endpush
