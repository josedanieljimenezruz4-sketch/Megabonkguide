@extends('layouts.admin')

@section('title', 'Gestionar Meta | MEGABONK GUIDE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="text-3xl font-bold text-white">⚙️ Gestionar Meta y Parches</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary" style="background: transparent; color: #aaa; border: 1px solid #444; padding: 8px 15px; border-radius: 6px; text-decoration: none;">← Volver al Panel</a>
    </div>

    @if(session('success'))
        <div style="background: rgba(46, 125, 50, 0.2); border: 1px solid #2e7d32; color: #a5d6a7; padding: 10px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Estrategias -->
        <div style="background: #1e1e2e; padding: 25px; border-radius: 8px; border: 1px solid #333;">
            <h2 style="color: #ffcf00; font-size: 1.5em; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Estrategias Dominantes</h2>
            
            <form action="{{ route('admin.meta.strategies.store') }}" method="POST" style="margin-bottom: 30px; background: #2a2a3c; padding: 15px; border-radius: 6px;">
                @csrf
                <h3 style="color: #fff; margin-bottom: 10px;">Añadir Nueva Estrategia</h3>
                <div style="margin-bottom: 10px;">
                    <input type="text" name="title" placeholder="Título (Ej: 1. El Bonk Perpetuo)" required style="width: 100%; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <textarea name="description" placeholder="Descripción de la estrategia..." required style="width: 100%; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;" rows="3"></textarea>
                </div>
                <div style="margin-bottom: 10px;">
                    <input type="text" name="build_type" placeholder="Tipo de Build vinculado (Ej: DPS, Tanque)" style="width: 100%; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;">
                    <small style="color: #888;">Debe coincidir exactamente con el tipo de build que usan los jugadores.</small>
                </div>
                <button type="submit" style="background: #ff4757; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Guardar Estrategia</button>
            </form>

            <ul style="list-style: none; padding: 0; margin: 0;">
                @forelse($strategies as $strategy)
                    <li style="background: #2a2a3c; padding: 15px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #ffcf00;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <strong style="color: #fff; font-size: 1.1em;">{{ $strategy->title }}</strong>
                                <span style="background: #333; color: #aaa; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; margin-left: 10px;">Vinculado: {{ $strategy->build_type ?? 'Ninguno' }}</span>
                                <p style="color: #aaa; font-size: 0.9em; margin-top: 5px;">{{ Str::limit($strategy->description, 100) }}</p>
                            </div>
                            <div style="display: flex;">
                                <button type="button" style="background: transparent; color: #36d1dc; border: none; cursor: pointer; font-size: 1.2em; margin-right: 5px;" onclick="openEditStrategyModal({{ $strategy->id }}, '{{ addslashes($strategy->title) }}', '{{ addslashes($strategy->build_type) }}', '{{ base64_encode($strategy->description) }}')">✎</button>
                                <form action="{{ route('admin.meta.strategies.destroy', $strategy->id) }}" method="POST" onsubmit="return confirm('¿Eliminar estrategia?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: transparent; color: #ff4757; border: none; cursor: pointer; font-size: 1.2em;">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <p style="color: #888; font-style: italic;">No hay estrategias guardadas.</p>
                @endforelse
            </ul>
        </div>

        <!-- Notas de Parche -->
        <div style="background: #1e1e2e; padding: 25px; border-radius: 8px; border: 1px solid #333;">
            <h2 style="color: #1da1f2; font-size: 1.5em; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Notas de Parche</h2>
            
            <form action="{{ route('admin.meta.patch_notes.store') }}" method="POST" style="margin-bottom: 30px; background: #2a2a3c; padding: 15px; border-radius: 6px;">
                @csrf
                <h3 style="color: #fff; margin-bottom: 10px;">Añadir Nota</h3>
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="version" placeholder="Versión (Ej: 3.1)" style="flex: 1; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;">
                    <select name="change_type" required style="flex: 1; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;">
                        <option value="buff">Buff (Mejora)</option>
                        <option value="nerf">Nerf (Debilitamiento)</option>
                        <option value="new">New (Nuevo Contenido)</option>
                    </select>
                </div>
                <div style="margin-bottom: 10px;">
                    <textarea name="description" placeholder="Ej: + Mejora al Hacha Púrpura..." required style="width: 100%; padding: 8px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 4px;" rows="3"></textarea>
                </div>
                <button type="submit" style="background: #1da1f2; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Guardar Nota</button>
            </form>

            <ul style="list-style: none; padding: 0; margin: 0;">
                @forelse($patchNotes as $note)
                    <li style="background: #2a2a3c; padding: 15px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid {{ $note->change_type == 'buff' ? '#2e7d32' : ($note->change_type == 'nerf' ? '#d32f2f' : '#fbc02d') }};">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <strong style="color: #fff; font-size: 1em;">
                                    [{{ $note->version ?? 'Global' }}] 
                                    <span style="color: {{ $note->change_type == 'buff' ? '#a5d6a7' : ($note->change_type == 'nerf' ? '#ef9a9a' : '#fff59d') }};">{{ strtoupper($note->change_type) }}</span>
                                </strong>
                                <p style="color: #ccc; font-size: 0.9em; margin-top: 5px;">{{ $note->description }}</p>
                            </div>
                            <div style="display: flex;">
                                <button type="button" style="background: transparent; color: #36d1dc; border: none; cursor: pointer; font-size: 1.2em; margin-right: 5px;" onclick="openEditPatchModal({{ $note->id }}, '{{ addslashes($note->version) }}', '{{ $note->change_type }}', '{{ base64_encode($note->description) }}')">✎</button>
                                <form action="{{ route('admin.meta.patch_notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('¿Eliminar nota?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: transparent; color: #ff4757; border: none; cursor: pointer; font-size: 1.2em;">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <p style="color: #888; font-style: italic;">No hay notas guardadas.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="editStrategyModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #1e1e2e; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; border: 1px solid #ffcf00; box-shadow: 0 0 20px rgba(255, 207, 0, 0.2);">
        <h2 style="color: #ffcf00; margin-top: 0;">Editar Estrategia</h2>
        <form id="editStrategyForm" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf @method('PUT')
            <input type="text" name="title" id="editStrategyTitle" required style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <input type="text" name="build_type" id="editStrategyType" style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <textarea name="description" id="editStrategyDesc" required rows="4" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('editStrategyModal').style.display='none'" style="padding: 10px; background: #555; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px; background: #ffcf00; color: black; font-weight: bold; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="editPatchModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #1e1e2e; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; border: 1px solid #1da1f2; box-shadow: 0 0 20px rgba(29, 161, 242, 0.2);">
        <h2 style="color: #1da1f2; margin-top: 0;">Editar Nota de Parche</h2>
        <form id="editPatchForm" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf @method('PUT')
            <div style="display: flex; gap: 10px;">
                <input type="text" name="version" id="editPatchVersion" style="flex: 1; padding: 10px; background: #222; border: 1px solid #444; color: white;">
                <select name="change_type" id="editPatchType" required style="flex: 1; padding: 10px; background: #222; border: 1px solid #444; color: white;">
                    <option value="buff">Buff (Mejora)</option>
                    <option value="nerf">Nerf (Debilitamiento)</option>
                    <option value="new">New (Nuevo Contenido)</option>
                </select>
            </div>
            <textarea name="description" id="editPatchDesc" required rows="4" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('editPatchModal').style.display='none'" style="padding: 10px; background: #555; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px; background: #1da1f2; color: white; font-weight: bold; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditStrategyModal(id, title, type, b64desc) {
    document.getElementById('editStrategyForm').action = '/admin/meta/strategies/' + id;
    document.getElementById('editStrategyTitle').value = title;
    document.getElementById('editStrategyType').value = type;
    try {
        document.getElementById('editStrategyDesc').value = decodeURIComponent(escape(atob(b64desc)));
    } catch(e) {
        document.getElementById('editStrategyDesc').value = atob(b64desc);
    }
    document.getElementById('editStrategyModal').style.display = 'flex';
}

function openEditPatchModal(id, version, type, b64desc) {
    document.getElementById('editPatchForm').action = '/admin/meta/patch-notes/' + id;
    document.getElementById('editPatchVersion').value = version;
    document.getElementById('editPatchType').value = type;
    try {
        document.getElementById('editPatchDesc').value = decodeURIComponent(escape(atob(b64desc)));
    } catch(e) {
        document.getElementById('editPatchDesc').value = atob(b64desc);
    }
    document.getElementById('editPatchModal').style.display = 'flex';
}
</script>
@endsection
