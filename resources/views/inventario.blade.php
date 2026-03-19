@extends('layouts.app')

@section('title', 'Mi Inventario | MEGABONK GUIDE')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
@endpush

@section('content')
<main class="main-content-catalogo" style="min-height: 80vh; padding: 40px;">
    <h1 class="page-title" style="text-align: center; color: #ff416c; margin-bottom: 40px;">🎒 Mi Inventario Completo</h1>
    
    @if($items->isEmpty())
        <div style="text-align:center; color:white; font-size:1.2rem; background:#1e1e24; padding:50px; border-radius:12px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
            <p>Aún no has desbloqueado ningún ítem en la guía. ¡Sigue jugando y marca tus progresos en el catálogo!</p>
            <a href="{{ route('unlocks.weapons') }}" style="display:inline-block; font-weight:bold; background: linear-gradient(90deg, #ff4b2b, #ff416c); color:white; padding:12px 24px; text-decoration:none; border-radius:6px; margin-top:20px;">Ir al Catálogo</a>
        </div>
    @else
        <p style="text-align:center; color: #b9bbbe; margin-bottom: 30px; font-size:1.1rem;">Aquí puedes ver todos los hallazgos que has recolectado a lo largo de tus partidas agrupados en un solo lugar seguro.</p>
        <section class="catalogo-grid">
            @foreach($items as $item)
            <div class="item-card" style="position:relative; background-color: #2c2f33;">
                <div style="position:absolute; top:-10px; right:-10px; background:#00e676; padding:5px 12px; border-radius:20px; font-weight:bold; color:#1e1e24; font-size:0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">Desbloqueado</div>
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 70px; height: 70px; object-fit: contain; margin-bottom: 15px; border-radius: 8px; background-color: #1a1a20; padding: 5px; box-shadow: inset 0 0 5px rgba(0,0,0,0.5); border: 1px solid #ff416c;">
                @else
                    <span class="card-icon" style="font-size: 2.5rem; color: #ff416c;">💎</span>
                @endif
                <h2 style="font-size: 1.3rem; margin-bottom: 5px;">{{ $item->name }}</h2>
                <span style="color:#ff4b2b; font-weight:bold; font-size:0.85rem; text-transform:uppercase; letter-spacing: 1px; display:inline-block; padding: 4px 8px; background: rgba(255, 65, 108, 0.1); border-radius: 4px;">{{ $item->type }}</span>
            </div>
            @endforeach
        </section>
    @endif
</main>
@endsection
