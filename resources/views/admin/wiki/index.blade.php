@extends('layouts.admin')

@section('title', 'Gestión Wiki | MEGABONK GUIDE')

@section('content')
    <div class="admin-wiki-container">
        <h1 class="admin-wiki-title">📚 Gestión de la Wiki (Información General)</h1>

        @if(session('success'))
            <div class="admin-wiki-alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-wiki-grid">

            <!-- ================= GAME INFO ================= -->
            <section class="admin-wiki-card-info">
                <h2>Sobre el Juego / Información</h2>

                <form action="{{ route('admin.wiki.game_infos.store') }}" method="POST" class="admin-wiki-form">
                    @csrf
                    <h4>Añadir Nueva Información</h4>
                    <input type="text" name="title" placeholder="Título" required class="admin-wiki-input">
                    <input type="text" name="category" placeholder="Categoría (Ej: Sobre el juego, Mecánicas)"
                        class="admin-wiki-input">
                    <textarea name="content" placeholder="Contenido (Soporta Markdown)" required rows="4"
                        class="admin-wiki-textarea"></textarea>
                    <button type="submit" class="admin-wiki-btn-info">Guardar Información</button>
                </form>

                <table class="admin-wiki-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($informacion as $info)
                            <tr>
                                <td>{{ $info->title }}</td>
                                <td>{{ $info->category ?? '-' }}</td>
                                <td>
                                    <div class="admin-wiki-actions">
                                        <button type="button" class="admin-wiki-btn-edit"
                                            onclick="openEditInfoModal({{ $info->id }}, '{{ addslashes($info->title) }}', '{{ addslashes($info->category) }}', '{{ base64_encode($info->content) }}')">✎</button>
                                        <form action="{{ route('admin.wiki.game_infos.destroy', $info->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="admin-wiki-btn-delete"
                                                onclick="return confirm('¿Eliminar?')">X</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- ================= FAQS ================= -->
            <section class="admin-wiki-card-faq">
                <h2>Preguntas Frecuentes (FAQ)</h2>

                <form action="{{ route('admin.wiki.faqs.store') }}" method="POST" class="admin-wiki-form">
                    @csrf
                    <h4>Añadir Nueva FAQ</h4>
                    <input type="text" name="title" placeholder="Pregunta" required class="admin-wiki-input">
                    <input type="text" name="category" placeholder="Categoría (Opcional)" class="admin-wiki-input">
                    <textarea name="content" placeholder="Respuesta (Soporta Markdown)" required rows="4"
                        class="admin-wiki-textarea"></textarea>
                    <button type="submit" class="admin-wiki-btn-faq">Guardar FAQ</button>
                </form>

                <table class="admin-wiki-table">
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preguntasFrecuentes as $faq)
                            <tr>
                                <td>{{ $faq->title }}</td>
                                <td>
                                    <div class="admin-wiki-actions">
                                        <button type="button" class="admin-wiki-btn-edit"
                                            onclick="openEditFaqModal({{ $faq->id }}, '{{ addslashes($faq->title) }}', '{{ addslashes($faq->category) }}', '{{ base64_encode($faq->content) }}')">✎</button>
                                        <form action="{{ route('admin.wiki.faqs.destroy', $faq->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="admin-wiki-btn-delete"
                                                onclick="return confirm('¿Eliminar?')">X</button>
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
    <div id="editInfoModal" class="admin-wiki-modal-overlay">
        <div class="admin-wiki-modal-info">
            <h2 class="admin-wiki-modal-title-info">Editar Información</h2>
            <form id="editInfoForm" method="POST" class="admin-wiki-modal-form">
                @csrf @method('PUT')
                <input type="text" name="title" id="editInfoTitle" required class="admin-wiki-input">
                <input type="text" name="category" id="editInfoCategory" class="admin-wiki-input">
                <textarea name="content" id="editInfoContent" required rows="6" class="admin-wiki-textarea"></textarea>
                <div class="admin-wiki-modal-actions">
                    <button type="button" onclick="document.getElementById('editInfoModal').style.display='none'"
                        class="admin-wiki-btn-cancel">Cancelar</button>
                    <button type="submit" class="admin-wiki-btn-save-info">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editFaqModal" class="admin-wiki-modal-overlay">
        <div class="admin-wiki-modal-faq">
            <h2 class="admin-wiki-modal-title-faq">Editar FAQ</h2>
            <form id="editFaqForm" method="POST" class="admin-wiki-modal-form">
                @csrf @method('PUT')
                <input type="text" name="title" id="editFaqTitle" required class="admin-wiki-input">
                <input type="text" name="category" id="editFaqCategory" class="admin-wiki-input">
                <textarea name="content" id="editFaqContent" required rows="6" class="admin-wiki-textarea"></textarea>
                <div class="admin-wiki-modal-actions">
                    <button type="button" onclick="document.getElementById('editFaqModal').style.display='none'"
                        class="admin-wiki-btn-cancel">Cancelar</button>
                    <button type="submit" class="admin-wiki-btn-save-faq">Guardar</button>
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
            } catch (e) {
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
            } catch (e) {
                document.getElementById('editFaqContent').value = atob(b64content);
            }
            document.getElementById('editFaqModal').style.display = 'flex';
        }
    </script>
@endpush