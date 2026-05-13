@extends('layouts.admin')

@section('title', 'Gestión de Usuarios | Admin')


@section('content')
    <div class="admin-header">
        <h1 class="admin-title">👥 Gestión de Usuarios</h1>
        <p>Administra, suspende o elimina cuentas de la plataforma.</p>
    </div>

    @if(session('success'))
        <div
            style="background: rgba(168, 255, 120, 0.2); border-left: 4px solid #a8ff78; padding: 15px; margin-bottom: 20px; color: #fff; border-radius: 4px;">
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
                {{-- La directiva @foreach itera sobre la colección de usuarios pasada desde el controlador --}}
                @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $user->avatar_url }}" alt="avatar"
                                    style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
                                <strong>{{ $user->username ?? $user->name }}</strong>
                                @if($user->is_admin) <span
                                    style="background: #a8ff78; color: #000; font-size: 0.7em; padding: 2px 6px; border-radius: 4px; font-weight: bold;">ADMIN</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->banned_until && \Carbon\Carbon::parse($user->banned_until)->isFuture())
                                <span class="banned-status">Suspendido hasta
                                    {{ \Carbon\Carbon::parse($user->banned_until)->format('d/m/Y') }}</span>
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
                                    <button type="button" class="admin-btn btn-ban"
                                        onclick="openBanModal({{ $user->id }}, '{{ addslashes($user->username ?? $user->name) }}')">Suspender</button>
                                @endif

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('¿SEGURO? Esta acción es IRREVERSIBLE y borrará todo el contenido de este usuario.');">
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
                    <button type="button" class="admin-btn" style="background: #555;"
                        onclick="closeBanModal()">Cancelar</button>
                    <button type="submit" class="admin-btn btn-ban">Aplicar Suspensión</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    {{--
    @push('scripts') nos permite inyectar el código JS específico de esta vista
    al final del

    <body> en la plantilla principal de layouts/admin.blade.php.
        --}}
        <script>
            function openBanModal(id, username) {
                document.getElementById('ban-user-name').textContent = username;
                document.getElementById('banForm').action = '/admin/users/' + id + '/ban';
                document.getElementById('banModal').style.display = 'flex';
            }

            function closeBanModal() {
                document.getElementById('banModal').style.display = 'none';
            }

            document.getElementById('banModal').addEventListener('click', function (e) {
                if (e.target === this) closeBanModal();
            });
        </script>
@endpush
