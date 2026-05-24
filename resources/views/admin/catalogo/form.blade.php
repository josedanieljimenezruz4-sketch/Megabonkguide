@extends('layouts.admin')

@section('title', 'Editar Ítem | Admin')

@section('content')
<div class="container mx-auto px-4 py-8" style="max-width: 700px;">
    <div class="mb-8">
        <a href="{{ route('admin.catalogo.index', ['type' => $item->type]) }}" class="text-gray-400 hover:text-white transition-colors text-sm inline-flex items-center gap-2 mb-4">
            ← Volver al Catálogo
        </a>
        <h1 class="text-3xl font-bold text-white flex items-center gap-3">✏️ Editar Ítem</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-500/20 border-l-4 border-red-500 p-4 mb-6 rounded">
            <ul class="text-red-400 text-sm list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-card p-8 rounded-2xl bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <form action="{{ route('admin.catalogo.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            {{-- ID (solo lectura) --}}
            <div>
                <label class="block text-cyan-400 font-bold text-sm mb-2">ID del Ítem</label>
                <input type="text" value="{{ $item->id }}" disabled
                       class="w-full px-4 py-3 rounded-lg bg-black/50 text-gray-500 border border-white/10 cursor-not-allowed font-mono text-sm">
                <p class="text-gray-500 text-xs mt-1">El ID no es editable una vez creado.</p>
            </div>

            {{-- Nombre --}}
            <div>
                <label for="name" class="block text-cyan-400 font-bold text-sm mb-2">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required
                       class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none focus:shadow-[0_0_10px_rgba(6,182,212,0.3)] transition-all">
                @error('name') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo --}}
            <div>
                <label for="type" class="block text-cyan-400 font-bold text-sm mb-2">Categoría</label>
                <select id="type" name="type" required
                        class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all cursor-pointer">
                    <option value="personaje" {{ old('type', $item->type) == 'personaje' ? 'selected' : '' }}>🧙 Personaje</option>
                    <option value="arma" {{ old('type', $item->type) == 'arma' ? 'selected' : '' }}>⚔️ Arma</option>
                    <option value="tomo" {{ old('type', $item->type) == 'tomo' ? 'selected' : '' }}>📖 Tomo</option>
                    <option value="item" {{ old('type', $item->type) == 'item' ? 'selected' : '' }}>💎 Ítem</option>
                </select>
                @error('type') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Descripción --}}
            <div>
                <label for="description" class="block text-cyan-400 font-bold text-sm mb-2">Descripción / Estadísticas</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all resize-vertical">{{ old('description', $item->description) }}</textarea>
                @error('description') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Requisito --}}
            <div>
                <label for="requirement" class="block text-cyan-400 font-bold text-sm mb-2">Requisito de Desbloqueo <span class="text-gray-500 font-normal">(Opcional)</span></label>
                <input type="text" id="requirement" name="requirement" value="{{ old('requirement', $item->requirement) }}"
                       placeholder="Ej: Alcanzar el nivel 10..."
                       class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all">
                @error('requirement') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Imagen --}}
            <div>
                <label class="block text-cyan-400 font-bold text-sm mb-2">Imagen Actual</label>
                @if($item->image_path)
                    <div class="flex items-center gap-4 mb-3 p-3 rounded-lg bg-black/30 border border-white/5">
                        <img src="{{ asset($item->image_url) }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                             class="w-16 h-16 object-contain rounded bg-black/50">
                        <span class="text-gray-400 text-sm font-mono">{{ $item->image_path }}</span>
                    </div>
                @else
                    <p class="text-gray-500 text-sm mb-3 italic">Sin imagen asignada.</p>
                @endif

                <label for="image" class="block text-gray-400 text-sm mb-2">Subir nueva imagen <span class="text-gray-500">(reemplaza la actual)</span></label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30 cursor-pointer transition-all">
                @error('image') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 mt-4">
                <button type="submit"
                        class="flex-1 py-3 rounded-lg font-bold text-sm bg-cyan-500 text-black hover:bg-cyan-400 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                    💾 Guardar Cambios
                </button>
                <a href="{{ route('admin.catalogo.index', ['type' => $item->type]) }}"
                   class="py-3 px-6 rounded-lg font-bold text-sm bg-white/10 text-gray-300 hover:bg-white/20 transition-all text-center border border-white/10">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
