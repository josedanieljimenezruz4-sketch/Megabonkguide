@extends('layouts.admin')

@section('title', 'Gestión de Sugerencias | Admin')

@push('styles')
    <style>
        .admin-header {
            margin-bottom: 30px;
        }
        .admin-title {
            color: #a8ff78; /* Verde neón */
            margin-bottom: 10px;
            font-size: 2rem;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(168, 255, 120, 0.4);
        }
        .admin-card {
            background-color: rgba(44, 47, 51, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: #eee;
        }
        .admin-table th {
            color: #a8ff78;
            text-transform: uppercase;
            font-size: 0.9rem;
            background: rgba(0,0,0,0.3);
        }
        .admin-btn {
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
        }
        .admin-btn-view {
            background: linear-gradient(90deg, #36d1dc, #5b86e5);
            box-shadow: 0 4px 10px rgba(54, 209, 220, 0.4);
        }
        .admin-btn-view:hover { opacity: 0.9; }
        
        .admin-btn-danger {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            box-shadow: 0 4px 10px rgba(255, 65, 108, 0.4);
        }
        .admin-btn-danger:hover { opacity: 0.9; }

        /* Status Select */
        .status-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .status-select {
            appearance: none;
            background: rgba(15, 15, 15, 0.8);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 8px 30px 8px 15px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            outline: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23FFFFFF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right .7em top 50%;
            background-size: .65em auto;
        }
        .status-select:hover {
            border-color: rgba(255,255,255,0.3);
        }
        .status-select option {
            background-color: #121212;
            color: #fff;
        }

        /* Pendiente */
        .status-select.pending {
            border-color: rgba(255, 223, 0, 0.4);
            color: #ffdf00;
        }
        .status-select.pending:focus {
            box-shadow: 0 0 10px rgba(255, 223, 0, 0.3);
        }
        .blinking-dot {
            width: 8px;
            height: 8px;
            background-color: #ffdf00;
            border-radius: 50%;
            position: absolute;
            left: -12px;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; box-shadow: 0 0 8px #ffdf00; }
            50% { opacity: 0.3; box-shadow: none; }
        }

        /* En Revisión */
        .status-select.reviewing {
            border-color: #00ffff;
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.5);
            color: #00ffff;
        }

        /* Completada */
        .status-select.completed {
            border-color: rgba(168, 255, 120, 0.2);
            color: rgba(168, 255, 120, 0.6);
            text-shadow: 0 0 5px rgba(168, 255, 120, 0.2);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background: #1e1e24;
            border: 1px solid rgba(168, 255, 120, 0.3);
            box-shadow: 0 0 20px rgba(168, 255, 120, 0.2);
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            padding: 30px;
            position: relative;
            color: white;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #ff4757;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .modal-subject {
            color: #a8ff78;
            font-size: 1.5rem;
            margin-bottom: 5px;
            margin-top: 0;
        }
        .modal-meta {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .modal-body {
            line-height: 1.6;
            white-space: pre-wrap;
            color: #ddd;
        }
    </style>
@endpush

@section('content')
<div class="admin-header">
    <h1 class="admin-title">📧 Bandeja de Sugerencias</h1>
    <p>Revisa las ideas y propuestas que la comunidad envía desde el Home.</p>
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
                <th>Asunto</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suggestions as $sug)
                <tr>
                    <td>#{{ $sug->id }}</td>
                    <td>
                        <strong>{{ $sug->name }}</strong><br>
                        <span style="font-size: 0.8em; color: #888;">
                            {{ $sug->user_id ? '(Registrado)' : '(Invitado)' }}
                        </span>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($sug->subject, 40) }}</td>
                    <td>{{ $sug->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="status-wrapper" id="status-wrapper-{{ $sug->id }}">
                            <span class="blinking-dot" style="display: {{ $sug->status == 'pending' ? 'block' : 'none' }}"></span>
                            <select onchange="updateSuggestionStatus({{ $sug->id }}, this)" class="status-select {{ $sug->status }}">
                                <option value="pending" {{ $sug->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="reviewing" {{ $sug->status == 'reviewing' ? 'selected' : '' }}>En Revisión</option>
                                <option value="completed" {{ $sug->status == 'completed' ? 'selected' : '' }}>Completada</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px;">
                            <button class="admin-btn admin-btn-view" onclick="openModal({{ $sug->id }}, '{{ addslashes($sug->subject) }}', '{{ addslashes($sug->name) }}', '{{ $sug->created_at->format('d/m/Y H:i') }}', '{{ base64_encode($sug->content) }}', this)">
                                👁️ Ver
                            </button>
                            <form action="{{ route('admin.suggestions.destroy', $sug->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar esta sugerencia?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn-danger">🗑️ Borrar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; padding: 30px;">
                        No hay sugerencias en la bandeja.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div style="margin-top: 20px;">
        {{ $suggestions->links() }}
    </div>
</div>

<!-- Modal -->
<div id="viewModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">✖</button>
        <h2 class="modal-subject" id="m-subject">Asunto</h2>
        <div class="modal-meta">
            Enviado por <strong id="m-name" style="color: white;">Nombre</strong> el <span id="m-date">Fecha</span>
        </div>
        <div class="modal-body" id="m-body">
            Contenido...
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id, subject, name, date, b64Content, btnElement) {
        document.getElementById('m-subject').textContent = subject;
        document.getElementById('m-name').textContent = name;
        document.getElementById('m-date').textContent = date;
        
        // Decodificamos el base64 para evitar problemas con comillas y saltos de línea
        try {
            const content = decodeURIComponent(escape(atob(b64Content)));
            document.getElementById('m-body').textContent = content;
        } catch(e) {
            document.getElementById('m-body').textContent = atob(b64Content);
        }

        document.getElementById('viewModal').style.display = 'flex';

        // Marcar como leído
        fetch(`/admin/suggestions/${id}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(res => res.json())
          .then(data => {
              if (data.success) {
                  // Opcional: reducir contador visualmente si es necesario
                  // El Sidebar se recargará la próxima vez, pero podemos hacerlo reactivo quitando el row highlight si lo hubiera.
              }
          });
    }

    function closeModal() {
        document.getElementById('viewModal').style.display = 'none';
    }

    // Cerrar al hacer clic fuera
    document.getElementById('viewModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function updateSuggestionStatus(id, selectElement) {
        const status = selectElement.value;
        const wrapper = document.getElementById('status-wrapper-' + id);
        const dot = wrapper.querySelector('.blinking-dot');

        // Efecto de cargando (opcional)
        selectElement.style.opacity = '0.5';

        fetch(`/admin/suggestions/${id}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            selectElement.style.opacity = '1';
            if (data.success) {
                // Actualizar clases de colores
                selectElement.className = 'status-select ' + data.status;
                
                // Mostrar/ocultar el punto parpadeante
                if (data.status === 'pending') {
                    dot.style.display = 'block';
                } else {
                    dot.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            selectElement.style.opacity = '1';
            alert('Error actualizando el estado.');
        });
    }
</script>
@endpush
