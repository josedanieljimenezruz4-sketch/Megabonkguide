@extends('layouts.admin')

@section('title', 'Gestión Wiki | MEGABONK GUIDE')

@section('content')
<div class="admin-container" style="max-width: 1000px; margin: 40px auto; padding: 20px; background-color: #1e1e24; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.4); color: #fff;">
    <h1 class="admin-title" style="text-align: center; color: #ff4b2b; margin-bottom: 30px; font-size: 2rem; text-transform: uppercase;">📚 Gestión de la Wiki (Información General)</h1>

    @if(session('success'))
        <div class="alert alert-success" style="background: rgba(0, 255, 128, 0.2); color: #0f8; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-grid" style="display: grid; grid-template-columns: 1fr; gap: 40px;">
        
        <!-- ================= GAME INFO ================= -->
        <section class="admin-card" style="background: #111; padding: 20px; border-radius: 10px; border-left: 4px solid var(--neon-cyan, #41E8EF);">
            <h2>Sobre el Juego / Información</h2>
            
            <form action="{{ route('admin.wiki.game_infos.store') }}" method="POST" style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 10px; background: #000; padding: 15px; border-radius: 8px;">
                @csrf
                <h4>Añadir Nueva Información</h4>
                <input type="text" name="title" placeholder="Título" required style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
                <input type="text" name="category" placeholder="Categoría (Ej: Sobre el juego, Mecánicas)" style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
                <textarea name="content" placeholder="Contenido (Soporta Markdown)" required rows="4" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
                <button type="submit" class="btn btn-primary" style="align-self: flex-start; background: #41E8EF; color: black; border: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; cursor: pointer;">Guardar Información</button>
            </form>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #333;">
                        <th style="padding: 10px;">Título</th>
                        <th style="padding: 10px;">Categoría</th>
                        <th style="padding: 10px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($infos as $info)
                    <tr style="border-bottom: 1px solid #222;">
                        <td style="padding: 10px;">{{ $info->title }}</td>
                        <td style="padding: 10px;">{{ $info->category ?? '-' }}</td>
                        <td style="padding: 10px;">
                            <div style="display: flex; gap: 5px;">
                                <button type="button" style="background: #36d1dc; color: #000; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; font-weight: bold;" onclick="openEditInfoModal({{ $info->id }}, '{{ addslashes($info->title) }}', '{{ addslashes($info->category) }}', '{{ base64_encode($info->content) }}')">✎</button>
                                <form action="{{ route('admin.wiki.game_infos.destroy', $info->id) }}" method="POST" style="display: inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;" onclick="return confirm('¿Eliminar?')">X</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- ================= FAQS ================= -->
        <section class="admin-card" style="background: #111; padding: 20px; border-radius: 10px; border-left: 4px solid var(--neon-purple, #B965F0);">
            <h2>Preguntas Frecuentes (FAQ)</h2>
            
            <form action="{{ route('admin.wiki.faqs.store') }}" method="POST" style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 10px; background: #000; padding: 15px; border-radius: 8px;">
                @csrf
                <h4>Añadir Nueva FAQ</h4>
                <input type="text" name="title" placeholder="Pregunta" required style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
                <input type="text" name="category" placeholder="Categoría (Opcional)" style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
                <textarea name="content" placeholder="Respuesta (Soporta Markdown)" required rows="4" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
                <button type="submit" class="btn btn-primary" style="align-self: flex-start; background: #B965F0; color: white; border: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; cursor: pointer;">Guardar FAQ</button>
            </form>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #333;">
                        <th style="padding: 10px;">Pregunta</th>
                        <th style="padding: 10px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                    <tr style="border-bottom: 1px solid #222;">
                        <td style="padding: 10px;">{{ $faq->title }}</td>
                        <td style="padding: 10px;">
                            <div style="display: flex; gap: 5px;">
                                <button type="button" style="background: #36d1dc; color: #000; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; font-weight: bold;" onclick="openEditFaqModal({{ $faq->id }}, '{{ addslashes($faq->title) }}', '{{ addslashes($faq->category) }}', '{{ base64_encode($faq->content) }}')">✎</button>
                                <form action="{{ route('admin.wiki.faqs.destroy', $faq->id) }}" method="POST" style="display: inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;" onclick="return confirm('¿Eliminar?')">X</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

    </div>
</div>

<!-- Modals de Edición -->
<div id="editInfoModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #1e1e24; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; border: 1px solid #41E8EF; box-shadow: 0 0 20px rgba(65, 232, 239, 0.2);">
        <h2 style="color: #41E8EF; margin-top: 0;">Editar Información</h2>
        <form id="editInfoForm" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf @method('PUT')
            <input type="text" name="title" id="editInfoTitle" required style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <input type="text" name="category" id="editInfoCategory" style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <textarea name="content" id="editInfoContent" required rows="6" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('editInfoModal').style.display='none'" style="padding: 10px; background: #555; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px; background: #41E8EF; color: black; font-weight: bold; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="editFaqModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #1e1e24; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; border: 1px solid #B965F0; box-shadow: 0 0 20px rgba(185, 101, 240, 0.2);">
        <h2 style="color: #B965F0; margin-top: 0;">Editar FAQ</h2>
        <form id="editFaqForm" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf @method('PUT')
            <input type="text" name="title" id="editFaqTitle" required style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <input type="text" name="category" id="editFaqCategory" style="padding: 10px; background: #222; border: 1px solid #444; color: white;">
            <textarea name="content" id="editFaqContent" required rows="6" style="padding: 10px; background: #222; border: 1px solid #444; color: white; resize: vertical;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('editFaqModal').style.display='none'" style="padding: 10px; background: #555; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px; background: #B965F0; color: white; font-weight: bold; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditInfoModal(id, title, category, b64content) {
    document.getElementById('editInfoForm').action = '/admin/wiki/game-infos/' + id;
    document.getElementById('editInfoTitle').value = title;
    document.getElementById('editInfoCategory').value = category;
    try {
        document.getElementById('editInfoContent').value = decodeURIComponent(escape(atob(b64content)));
    } catch(e) {
        document.getElementById('editInfoContent').value = atob(b64content);
    }
    document.getElementById('editInfoModal').style.display = 'flex';
}

function openEditFaqModal(id, title, category, b64content) {
    document.getElementById('editFaqForm').action = '/admin/wiki/faqs/' + id;
    document.getElementById('editFaqTitle').value = title;
    document.getElementById('editFaqCategory').value = category;
    try {
        document.getElementById('editFaqContent').value = decodeURIComponent(escape(atob(b64content)));
    } catch(e) {
        document.getElementById('editFaqContent').value = atob(b64content);
    }
    document.getElementById('editFaqModal').style.display = 'flex';
}
</script>
@endpush
