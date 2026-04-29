@extends('layouts.app')

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
                            <form action="{{ route('admin.wiki.game_infos.destroy', $info->id) }}" method="POST" style="display: inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;" onclick="return confirm('¿Eliminar?')">X</button>
                            </form>
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
                            <form action="{{ route('admin.wiki.faqs.destroy', $faq->id) }}" method="POST" style="display: inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;" onclick="return confirm('¿Eliminar?')">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

    </div>
</div>
@endsection
