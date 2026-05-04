@extends('layouts.admin')

@section('title', 'Editar Build | Admin')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">⚒️ Editar Build: {{ $build->name }}</h1>
    <p>Modifica los detalles o el equipamiento de esta build generada por un usuario.</p>
</div>

<div class="admin-card">
    <form action="{{ route('admin.moderation.builds.update', $build->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        @csrf @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="color: #41E8EF;">Nombre de la Build:</label>
                <input type="text" name="name" value="{{ old('name', $build->name) }}" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: white; margin-top: 5px;">
            </div>
            <div>
                <label style="color: #41E8EF;">Personaje Principal:</label>
                <select name="character_id" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: white; margin-top: 5px;">
                    @foreach($personajes as $pj)
                        <option value="{{ $pj->id }}" {{ $build->character_id == $pj->id ? 'selected' : '' }}>{{ $pj->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label style="color: #41E8EF;">Descripción / Estrategia:</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: white; margin-top: 5px; resize: vertical;">{{ old('description', $build->description) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="color: #B965F0;">Tipo de Build:</label>
                <select name="type" style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: white; margin-top: 5px;">
                    <option value="">(Sin especificar)</option>
                    <option value="PvE" {{ $build->type == 'PvE' ? 'selected' : '' }}>PvE (Historia/Mazmorras)</option>
                    <option value="PvP" {{ $build->type == 'PvP' ? 'selected' : '' }}>PvP (Arena)</option>
                    <option value="Boss" {{ $build->type == 'Boss' ? 'selected' : '' }}>Bosses</option>
                </select>
            </div>
            <div>
                <label style="color: #B965F0;">Vincular al Meta (Estrategia):</label>
                <select name="meta_strategy_id" style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: white; margin-top: 5px;">
                    <option value="">No vinculada a meta</option>
                    @foreach($strategies as $strat)
                        <option value="{{ $strat->id }}" {{ $build->meta_strategy_id == $strat->id ? 'selected' : '' }}>{{ $strat->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">

        <h3 style="color: white;">Equipamiento (Slots)</h3>
        @php
            // Pre-process selected items
            $selectedArmas = $build->items->where('pivot.slot_type', 'Arma')->pluck('id')->toArray();
            $selectedTomos = $build->items->where('pivot.slot_type', 'Tomo')->pluck('id')->toArray();
            $selectedAccesorios = $build->items->where('pivot.slot_type', 'Item')->pluck('id')->toArray();
        @endphp

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <!-- Armas (Max 4) -->
            <div>
                <h4 style="color: #ff416c;">Armas (Max 4)</h4>
                @for($i=0; $i<4; $i++)
                    <select name="items[Arma][]" style="width: 100%; padding: 8px; background: #222; border: 1px solid #444; color: white; margin-bottom: 5px;">
                        <option value="">-- Seleccionar Arma --</option>
                        @foreach($armas as $arma)
                            <option value="{{ $arma->id }}" {{ (isset($selectedArmas[$i]) && $selectedArmas[$i] == $arma->id) ? 'selected' : '' }}>{{ $arma->name }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>

            <!-- Tomos (Max 4) -->
            <div>
                <h4 style="color: #f7b733;">Tomos (Max 4)</h4>
                @for($i=0; $i<4; $i++)
                    <select name="items[Tomo][]" style="width: 100%; padding: 8px; background: #222; border: 1px solid #444; color: white; margin-bottom: 5px;">
                        <option value="">-- Seleccionar Tomo --</option>
                        @foreach($tomos as $tomo)
                            <option value="{{ $tomo->id }}" {{ (isset($selectedTomos[$i]) && $selectedTomos[$i] == $tomo->id) ? 'selected' : '' }}>{{ $tomo->name }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>

            <!-- Accesorios (Max 6) -->
            <div>
                <h4 style="color: #a8ff78;">Accesorios (Max 6)</h4>
                @for($i=0; $i<6; $i++)
                    <select name="items[Item][]" style="width: 100%; padding: 8px; background: #222; border: 1px solid #444; color: white; margin-bottom: 5px;">
                        <option value="">-- Seleccionar Accesorio --</option>
                        @foreach($accesorios as $acc)
                            <option value="{{ $acc->id }}" {{ (isset($selectedAccesorios[$i]) && $selectedAccesorios[$i] == $acc->id) ? 'selected' : '' }}>{{ $acc->name }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <a href="{{ route('admin.moderation.index') }}" class="admin-btn" style="background: #444; text-decoration: none; text-align: center; width: 150px;">Cancelar</a>
            <button type="submit" class="admin-btn" style="background: #36d1dc; color: #000; font-size: 1.1rem; width: 200px;">💾 Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection
