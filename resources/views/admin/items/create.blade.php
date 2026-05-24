@extends('layouts.admin')

@section('title', 'Añadir Ítem | Admin | MEGABONK GUIDE')

@section('content')
    <div class="container mx-auto px-4 py-8" style="max-width: 700px;">
        <div class="mb-8">
            <a href="{{ route('admin.catalogo.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm inline-flex items-center gap-2 mb-4">
                ← Volver al Catálogo
            </a>
            <h2 class="text-3xl font-bold text-white flex items-center gap-3">⚔️ Añadir Nuevo Ítem</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border-l-4 border-green-500 p-4 mb-6 text-white rounded shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="glass-card p-8 rounded-2xl bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf

                {{-- ID --}}
                <div>
                    <label for="id" class="block text-cyan-400 font-bold text-sm mb-2">ID Original HTML (Ej: "hacha-purpura")</label>
                    <input type="text" id="id" name="id" value="{{ old('id') }}" required
                           class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none focus:shadow-[0_0_10px_rgba(6,182,212,0.3)] transition-all"
                           placeholder="ej: espada-fuego">
                    @error('id') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-cyan-400 font-bold text-sm mb-2">Nombre Público del Ítem</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none focus:shadow-[0_0_10px_rgba(6,182,212,0.3)] transition-all">
                    @error('name') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <label for="type" class="block text-cyan-400 font-bold text-sm mb-2">Categoría</label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all cursor-pointer">
                        <option value="personaje" {{ old('type') == 'personaje' ? 'selected' : '' }}>🧙 Personaje</option>
                        <option value="arma" {{ old('type') == 'arma' ? 'selected' : '' }}>⚔️ Arma</option>
                        <option value="tomo" {{ old('type') == 'tomo' ? 'selected' : '' }}>📖 Tomo</option>
                        <option value="item" {{ old('type') == 'item' ? 'selected' : '' }}>💎 Ítem</option>
                    </select>
                    @error('type') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="block text-cyan-400 font-bold text-sm mb-2">Descripción / Estadísticas</label>
                    <textarea id="description" name="description" rows="3" required
                              class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all resize-vertical">{{ old('description') }}</textarea>
                    @error('description') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Requisito --}}
                <div>
                    <label for="requirement" class="block text-cyan-400 font-bold text-sm mb-2">Requisito de Desbloqueo <span class="text-gray-500 font-normal">(Opcional)</span></label>
                    <input type="text" id="requirement" name="requirement" value="{{ old('requirement') }}"
                           placeholder="Ej: Alcanzar el nivel 10..."
                           class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 focus:border-cyan-500 focus:outline-none transition-all">
                    @error('requirement') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Imagen --}}
                <div>
                    <label for="image" class="block text-cyan-400 font-bold text-sm mb-2">Imagen o Icono (.png, .webp, .jpg)</label>
                    <input type="file" id="image" name="image" accept="image/*" required
                           class="w-full px-4 py-3 rounded-lg bg-black/50 text-white border border-white/10 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30 cursor-pointer transition-all">
                    @error('image') <p style="color: #ff4444; font-size: 0.85em; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-lg font-bold text-sm bg-green-500 text-white hover:bg-green-400 transition-all shadow-[0_0_15px_rgba(34,197,94,0.3)] hover:shadow-[0_0_25px_rgba(34,197,94,0.5)] mt-2">
                    💾 Registrar y Subir a Storage
                </button>
            </form>
        </div>
    </div>
@endsection
