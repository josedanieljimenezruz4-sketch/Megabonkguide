<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier Lists de la Comunidad | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('iconotlabaho.webp') }}" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-tierlist">

        <h1 class="page-title">Tier Lists de la Comunidad</h1>

        <p class="intro-text-tierlist" style="text-align: center;">
            Explora las clasificaciones y opiniones creadas por otros jugadores.
        </p>

        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <a href="{{ route('community-tierlists.create') }}" class="btn btn-primary-link" style="padding: 10px 20px; font-size: 1em; background: #ff4757; color: white;">+ Crear tu Propia Tier List</a>
        </div>

        <div class="tier-filters" style="margin-bottom: 40px;">
            <a href="{{ route('community-tierlists.index') }}" class="filter-btn filter-all {{ !$categoria || $categoria == 'general' ? 'active' : '' }}">Todas</a>
            <a href="{{ route('community-tierlists.index', ['categoria' => 'personaje']) }}" class="filter-btn filter-personajes {{ $categoria == 'personaje' ? 'active' : '' }}">Personajes</a>
            <a href="{{ route('community-tierlists.index', ['categoria' => 'arma']) }}" class="filter-btn filter-armas {{ $categoria == 'arma' ? 'active' : '' }}">Armas</a>
            <a href="{{ route('community-tierlists.index', ['categoria' => 'tomo']) }}" class="filter-btn filter-tomos {{ $categoria == 'tomo' ? 'active' : '' }}">Tomos</a>
            <a href="{{ route('community-tierlists.index', ['categoria' => 'item']) }}" class="filter-btn filter-items {{ $categoria == 'item' ? 'active' : '' }}">Ítems</a>
        </div>

        @if($tierLists->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px;">
                @foreach($tierLists as $ctl)
                    <a href="{{ route('community-tierlists.show', $ctl->id) }}" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                        <div style="background: #2c2f33; padding: 15px; border-radius: 8px; border-left: 4px solid #ffcf00; transition: transform 0.2s, background 0.2s; cursor: pointer; height: 100%; box-sizing: border-box;">
                            <h3 style="margin: 0 0 5px 0; font-size: 1.2em; color: #fff;">{{ $ctl->titulo }}</h3>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9em; color: #aaa; margin-bottom: 15px;">
                                <span>Por: <strong>{{ $ctl->user->username ?? 'Anónimo' }}</strong></span>
                                <span>Cat: {{ ucfirst($ctl->categoria) }}</span>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                @php
                                    $miniRanks = ['S' => '#ff7f7f', 'A' => '#ffbf7f', 'B' => '#ffff7f', 'C' => '#bfff7f', 'D' => '#7fffb2', 'E' => '#7fffff', 'F' => '#aaaaaa'];
                                @endphp
                                @foreach(['S', 'A', 'B', 'C', 'D', 'E', 'F'] as $r)
                                    @php
                                        $itemsInRank = $ctl->rows->where('rank', $r);
                                    @endphp
                                    @if($itemsInRank->count() > 0)
                                        <div style="display: flex; align-items: center; background: #1a1a1a; border-radius: 4px; overflow: hidden; height: 28px;">
                                            <div style="background: {{ $miniRanks[$r] }}; color: #000; font-weight: bold; width: 28px; text-align: center; line-height: 28px; font-size: 0.85em; flex-shrink: 0;">{{ $r }}</div>
                                            <div style="display: flex; gap: 3px; padding-left: 5px; overflow: hidden; align-items: center;">
                                                @foreach($itemsInRank->take(8) as $row)
                                                    @if($row->item)
                                                        @php
                                                            $imgSrc = asset('images/' . $row->item->image_path);
                                                            if (\Illuminate\Support\Str::startsWith($row->item->image_path, 'items/')) $imgSrc = asset('storage/' . $row->item->image_path);
                                                        @endphp
                                                        <img src="{{ $row->item->image_path ? $imgSrc : asset('images/placeholder.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';" style="width: 22px; height: 22px; object-fit: contain; border-radius: 3px; background: #222;">
                                                    @endif
                                                @endforeach
                                                @if($itemsInRank->count() > 8)
                                                    <span style="color: #666; font-size: 0.8em; margin-left: 2px;">+{{ $itemsInRank->count() - 8 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div style="display: flex; justify-content: center; margin-top: 30px;" class="custom-pagination">
                {{ $tierLists->links() }}
            </div>
        @else
            <p style="text-align: center; color: #aaa; padding: 40px 0; font-size: 1.2em;">
                No hay Tier Lists creadas en esta categoría todavía. ¡Anímate a ser el primero!
            </p>
        @endif

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('tierlist') }}" class="btn btn-secondary-link">Regresar a la Tier List Oficial</a>
        </div>

    </main>

    <footer class="main-footer" style="margin-top: 50px;">
        <div class="footer-copy">
            &copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.
        </div>
    </footer>

</body>
</html>
