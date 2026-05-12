@extends('layouts.admin')

@section('title', 'Sugerencias de Tier')

@section('content')
<div class="admin-container">
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px;">
        <h1 style="color: #0ff; text-shadow: 0 0 10px rgba(0, 255, 255, 0.5); margin: 0;">Sugerencias de Tier</h1>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form action="{{ route('admin.meta.reset') }}" method="POST" style="margin: 0;" onsubmit="return confirm('⚠️ ¿REINICIAR TODA LA META?\n\nEsto pondrá TODOS los rangos a null.\nUsa transacción de BD.\n\n¿Confirmar?')">
                @csrf
                <button type="submit" style="background: rgba(255, 0, 0, 0.15); border: 1px solid #ff0000; color: #ff4444; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.3)'; this.style.boxShadow='0 0 12px rgba(255,0,0,0.5)'" onmouseout="this.style.background='rgba(255,0,0,0.15)'; this.style.boxShadow='none'">
                    ⚠️ Reiniciar Meta
                </button>
            </form>
            <a href="{{ route('admin.dashboard') }}" style="background: #222; border: 1px solid #444; color: #fff; padding: 8px 15px; border-radius: 5px; text-decoration: none;">&larr; Volver</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(0, 255, 0, 0.1); border: 1px solid #0f0; color: #0f0; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #1a1a1a; border-radius: 10px; border: 1px solid #333; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        <h2 style="color: #fff; margin-top: 0; margin-bottom: 20px;">Sugerencias Agrupadas por Ítem</h2>
        
        @if($agrupadas->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($agrupadas as $grupo)
                    <div style="background: #222; border-radius: 8px; border: 1px solid #333; padding: 15px; transition: all 0.3s;" onmouseover="this.style.borderColor='#0ff'" onmouseout="this.style.borderColor='#333'">
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            {{-- Icono del Ítem --}}
                            @if($grupo->item)
                                @php
                                    $imgSrc = asset('images/' . $grupo->item->image_path);
                                    if (\Illuminate\Support\Str::startsWith($grupo->item->image_path, 'items/')) $imgSrc = asset('storage/' . $grupo->item->image_path);
                                @endphp
                                <img src="{{ $grupo->item->image_path ? $imgSrc : asset('images/placeholder.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; background: #111; border: 1px solid #444; flex-shrink: 0;">
                            @endif

                            {{-- Nombre y conteo --}}
                            <div style="flex-grow: 1;">
                                <div style="font-size: 1.1em; font-weight: bold; color: #fff; margin-bottom: 6px;">
                                    {{ $grupo->item->name ?? 'Ítem #' . $grupo->item_id }}
                                    <span style="color: #aaa; font-weight: normal; font-size: 0.85em; margin-left: 8px;">{{ $grupo->total_votos }} voto{{ $grupo->total_votos > 1 ? 's' : '' }}</span>
                                </div>
                                {{-- Badges de conteo por rango --}}
                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                    @php
                                        $rankColors = ['S' => '#FFD700', 'A' => '#FF3131', 'B' => '#FF5E13', 'C' => '#FFF01F', 'D' => '#39FF14', 'E' => '#00FFEF', 'F' => '#6D6D6D'];
                                    @endphp
                                    @foreach($grupo->conteo_rangos as $tier => $count)
                                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 4px; background: {{ $rankColors[$tier] ?? '#555' }}22; border: 1px solid {{ $rankColors[$tier] ?? '#555' }}; color: {{ $rankColors[$tier] ?? '#ccc' }}; font-size: 0.85em; font-weight: bold;">
                                            {{ $tier }}: {{ $count }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Selector de rango + Aprobar --}}
                            <div style="display: flex; gap: 6px; flex-shrink: 0; align-items: center;">
                                <form action="{{ route('admin.tier-suggestions.approveMajority', $grupo->item_id) }}" method="POST" style="margin: 0; display: flex; gap: 6px; align-items: center;" onsubmit="return confirm('¿Asignar rango ' + this.querySelector('select').value + ' a este ítem?')">
                                    @csrf
                                    <select name="rank" style="background: #111; color: #0ff; border: 1px solid #0ff; border-radius: 4px; padding: 5px 8px; font-size: 0.85em; cursor: pointer; outline: none;">
                                        @foreach(['S','A','B','C','D','E','F'] as $r)
                                            <option value="{{ $r }}" {{ $grupo->rango_mayoritario === $r ? 'selected' : '' }} style="color: {{ $rankColors[$r] ?? '#fff' }};">{{ $r }}{{ $grupo->rango_mayoritario === $r ? ' ★' : '' }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" style="background: rgba(0, 255, 0, 0.1); border: 1px solid #0f0; color: #0f0; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-size: 0.85em; transition: all 0.3s; white-space: nowrap;" onmouseover="this.style.background='rgba(0, 255, 0, 0.2)'; this.style.boxShadow='0 0 8px rgba(0,255,0,0.5)'" onmouseout="this.style.background='rgba(0, 255, 0, 0.1)'; this.style.boxShadow='none'">
                                        ✅ Aprobar
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Detalle de usuarios (colapsable) --}}
                        <details style="margin-top: 10px;">
                            <summary style="cursor: pointer; color: #aaa; font-size: 0.85em; user-select: none;">Ver votos individuales ({{ $grupo->total_votos }})</summary>
                            <div style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($grupo->usuarios as $u)
                                    <div style="display: flex; align-items: center; gap: 6px; background: #1a1a1a; padding: 4px 10px; border-radius: 4px; border: 1px solid #333; font-size: 0.8em;">
                                        <span style="color: #ffcf00; font-weight: bold;">{{ $u['username'] }}</span>
                                        <span style="color: {{ $rankColors[$u['tier']] ?? '#ccc' }}; font-weight: bold;">{{ $u['tier'] }}</span>
                                        <span style="color: #666;">{{ $u['fecha'] }}</span>
                                        {{-- Ban individual --}}
                                        <form action="{{ route('admin.tier-suggestions.ban', $u['id']) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #ff4757; cursor: pointer; font-size: 0.9em; padding: 0 2px;" onclick="return confirm('¿Eliminar este voto?')" title="Banear voto">🔨</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #aaa; background: #222; border-radius: 8px; border: 1px dashed #444;">
                <p style="font-size: 1.2em; margin-bottom: 10px;">🎉 Todo al día</p>
                <p style="margin: 0;">No hay sugerencias de tier pendientes por revisar.</p>
            </div>
        @endif
    </div>
</div>
@endsection
