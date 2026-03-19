@extends('layouts.app')

@section('title', 'Añadir Ítem | Admin | MEGABONK GUIDE')

@push('styles')
<style>
    .admin-form-card { max-width: 600px; margin: 40px auto; background: #1e1e24; padding: 30px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
    .form-group label { display: block; margin-bottom: 8px; color: #ff416c; font-weight: bold; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #3f4247; background: #2c2f33; color: white; margin-bottom: 15px; }
    .btn-submit { background: linear-gradient(90deg, #ff4b2b, #ff416c); color: white; border: none; padding: 10px 20px; font-size: 1rem; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; }
    .alert-success { background-color: rgba(40, 167, 69, 0.2); color: #4eff7a; border: 1px solid #28a745; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight:bold; }
</style>
@endpush

@section('content')
<div class="admin-form-card">
    <h2 style="text-align:center; color:#ff4b2b; margin-bottom: 20px;">Añadir Nuevo Ítem ⚔️</h2>
    
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>ID Original HTML (Ej: "hacha-purpura", "anillo-critico")</label>
            <input type="text" name="id" class="@error('id') input-error @enderror" value="{{ old('id') }}" required>
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
            <input type="text" name="requirement" value="{{ old('requirement') }}" placeholder="Ej: Alcanzar el nivel 10...">
        </div>
        <div class="form-group">
            <label>Imagen o Icono (.png, .webp, .jpg)</label>
            <input type="file" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn-submit">Registrar y Subir a Storage</button>
    </form>
</div>
@endsection
