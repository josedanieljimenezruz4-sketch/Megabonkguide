@extends('layouts.admin')

@section('title', 'Añadir Ítem | Admin | MEGABONK GUIDE')

@section('content')
    <div class="admin-form-card">
        <h2 class="admin-form-title">Añadir Nuevo Ítem ⚔️</h2>


        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            {{-- @csrf protege nuestro formulario de ataques Cross-Site Request Forgery --}}
            @csrf
            <div class="form-group">
                <label>ID Original HTML (Ej: "hacha-purpura", "anillo-critico")</label>
                <input type="text" name="id" class="@error('id') input-error @enderror" value="{{ old('id') }}" required>
                {{-- La directiva @error captura los fallos de validación devueltos por el controlador --}}
                @error('id') <div style="color:red; font-size:0.85rem;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Nombre Público del Ítem</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="type" required>
                    <option value="arma">Arma</option>
                    <option value="tomo">Tomo</option>
                    <option value="item">Ítem</option>
                    <option value="personaje">Personaje</option>
                </select>
            </div>
            <div class="form-group">
                <label>Descripción / Estadísticas</label>
                <textarea name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label>Requisito de Desbloqueo (Opcional)</label>
                <input type="text" name="requirement" value="{{ old('requirement') }}"
                    placeholder="Ej: Alcanzar el nivel 10...">
            </div>
            <div class="form-group">
                <label>Imagen o Icono (.png, .webp, .jpg)</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn-submit">Registrar y Subir a Storage</button>
        </form>
    </div>
@endsection