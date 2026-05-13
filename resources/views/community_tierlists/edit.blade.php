<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tier List de la Comunidad | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}" type="image/x-icon">
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; color: #ccc; }
        .form-control { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; background: #222; color: white; }
        .btn-submit { background: #ff4757; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .item-row { display: flex; align-items: center; justify-content: space-between; background: #2c2f33; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
        .item-info { display: flex; align-items: center; gap: 15px; }
        .item-info img { width: 40px; height: 40px; object-fit: contain; border-radius: 5px; background: #111; }
        .rank-select { padding: 5px; border-radius: 4px; background: #111; color: white; border: 1px solid #ff4757; }
    </style>
</head>

<body>
    @include('partials.header')

    <main class="main-content-tierlist" style="max-width: 800px;">
        <h1 class="page-title">Editar tu Tier List</h1>
        <p class="intro-text-tierlist" style="text-align: center;">
            Modifica las posiciones de tus ítems para reflejar el estado actual del juego.
        </p>

        <div style="text-align: center; margin-bottom: 30px; background: #2c2f33; padding: 15px; border-radius: 10px;">
            <span style="display:inline-block; margin-right:10px; font-weight:bold; color:white;">Categoría actual:</span>
            <span style="color: #ffcf00; font-weight: bold; text-transform: capitalize;">{{ $categoria }}</span>
        </div>

        <form action="{{ route('community-tierlists.update', $tierList->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div style="background: rgba(255,0,0,0.1); border: 1px solid #ff4757; color: #ff4757; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="titulo">Título de tu Tier List</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="{{ $tierList->titulo }}" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción (Opcional)</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ $tierList->descripcion }}</textarea>
            </div>

            <h2 style="color: #ffcf00; margin-top: 40px; margin-bottom: 20px; text-align: center;">Ordena tus unidades (Arrastra y suelta)</h2>
            
            <style>
                .dnd-tier-row { display: flex; background: #1a1a1a; margin-bottom: 5px; border-radius: 8px; overflow: hidden; min-height: 80px; }
                .dnd-tier-label { width: 80px; display: flex; align-items: center; justify-content: center; font-size: 1.5em; font-weight: bold; color: black; flex-shrink: 0; }
                .dnd-tier-zone { flex-grow: 1; padding: 10px; display: flex; flex-wrap: wrap; gap: 10px; min-height: 80px; align-content: flex-start; }
                .dnd-unranked-zone { background: #2c2f33; border: 2px dashed #444; border-radius: 8px; padding: 15px; min-height: 120px; display: flex; flex-wrap: wrap; gap: 10px; align-content: flex-start; justify-content: center; margin-bottom: 30px; }
                .dnd-item { width: 60px; height: 60px; cursor: grab; background: #222; border-radius: 5px; display: flex; align-items: center; justify-content: center; transition: transform 0.2s; position: relative; }
                .dnd-item:active { cursor: grabbing; transform: scale(1.1); }
                .dnd-item img { width: 100%; height: 100%; object-fit: contain; border-radius: 5px; pointer-events: none; }
                .dnd-item-tooltip { position: absolute; bottom: -20px; font-size: 0.7em; white-space: nowrap; background: rgba(0,0,0,0.8); color: white; padding: 2px 5px; border-radius: 3px; display: none; z-index: 10; pointer-events: none;}
                .dnd-item:hover .dnd-item-tooltip { display: block; }
            </style>

            <div class="dnd-board">
                @php
                    $tiers = [
                        'S' => '#ff7f7f',
                        'A' => '#ffbf7f',
                        'B' => '#ffff7f',
                        'C' => '#bfff7f',
                        'D' => '#7fffb2',
                        'E' => '#7fffff',
                        'F' => '#aaaaaa'
                    ];
                @endphp

                @foreach($tiers as $rank => $color)
                <div class="dnd-tier-row">
                    <div class="dnd-tier-label" style="background: {{ $color }};">{{ $rank }}</div>
                    <div class="dnd-tier-zone" ondrop="drop(event, '{{ $rank }}')" ondragover="allowDrop(event)">
                        @foreach($items as $item)
                            @if(isset($itemRanks[$item->id]) && $itemRanks[$item->id] === $rank)
                                @php
                                    $imageSrc = asset('images/' . $item->image_path);
                                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                        $imageSrc = asset('storage/' . $item->image_path);
                                    }
                                @endphp
                                <div class="dnd-item" draggable="true" ondragstart="drag(event, '{{ $item->id }}')" id="item-{{ $item->id }}">
                                    <img src="{{ $item->image_path ? $imageSrc : asset('images/placeholder.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                    <div class="dnd-item-tooltip">{{ $item->name }}</div>
                                    <input type="hidden" name="ranks[{{ $item->id }}]" id="input-{{ $item->id }}" value="{{ $rank }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <h3 style="color: #aaa; margin-top: 30px; margin-bottom: 15px; text-align: center;">Bandeja de Ítems</h3>
            <div class="dnd-unranked-zone" ondrop="drop(event, '')" ondragover="allowDrop(event)">
                @foreach($items as $item)
                    @if(!isset($itemRanks[$item->id]))
                        @php
                            $imageSrc = asset('images/' . $item->image_path);
                            if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                $imageSrc = asset('storage/' . $item->image_path);
                            }
                        @endphp
                        <div class="dnd-item" draggable="true" ondragstart="drag(event, '{{ $item->id }}')" id="item-{{ $item->id }}">
                            <img src="{{ $item->image_path ? $imageSrc : asset('images/placeholder.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                            <div class="dnd-item-tooltip">{{ $item->name }}</div>
                            <input type="hidden" name="ranks[{{ $item->id }}]" id="input-{{ $item->id }}" value="">
                        </div>
                    @endif
                @endforeach
            </div>

            <script>
                let draggingElementId = null;

                function allowDrop(ev) {
                    ev.preventDefault();
                    var container = ev.target.closest('.dnd-tier-zone') || ev.target.closest('.dnd-unranked-zone');
                    if (container && draggingElementId) {
                        var draggable = document.getElementById(draggingElementId);
                        if(draggable) {
                            var afterElement = getDragAfterElement(container, ev.clientX, ev.clientY);
                            if (afterElement == null) {
                                container.appendChild(draggable);
                            } else {
                                container.insertBefore(draggable, afterElement);
                            }
                        }
                    }
                }

                function getDragAfterElement(container, x, y) {
                    var draggableElements = [...container.querySelectorAll('.dnd-item:not(.is-dragging)')];
                    for (let child of draggableElements) {
                        let box = child.getBoundingClientRect();
                        if (y >= box.top - 10 && y <= box.bottom + 10) {
                            if (x < box.left + box.width / 2) {
                                return child;
                            }
                        }
                    }
                    return null;
                }

                function drag(ev, itemId) {
                    ev.dataTransfer.setData("text", 'item-' + itemId);
                    ev.dataTransfer.setData("itemId", itemId);
                    draggingElementId = 'item-' + itemId;
                    
                    setTimeout(() => {
                        var el = document.getElementById(draggingElementId);
                        if(el) { el.classList.add('is-dragging'); el.style.opacity = '0.5'; }
                    }, 0);
                }

                function drop(ev, rank) {
                    ev.preventDefault();
                    // Positioning is handled by allowDrop, here we just prevent defaults
                }

                // Autoscroll logic while dragging
                let scrollInterval;
                document.addEventListener('dragover', function(e) {
                    let threshold = 80;
                    let speed = 15;
                    let clientY = e.clientY;
                    
                    if (clientY < threshold) {
                        if (!scrollInterval) {
                            scrollInterval = setInterval(function() { window.scrollBy(0, -speed); }, 20);
                        }
                    } else if (window.innerHeight - clientY < threshold) {
                        if (!scrollInterval) {
                            scrollInterval = setInterval(function() { window.scrollBy(0, speed); }, 20);
                        }
                    } else {
                        if (scrollInterval) {
                            clearInterval(scrollInterval);
                            scrollInterval = null;
                        }
                    }
                });

                document.addEventListener('dragend', function(e) {
                    if (draggingElementId) {
                        var draggable = document.getElementById(draggingElementId);
                        if (draggable) {
                            var itemId = draggable.id.replace('item-', '');
                            draggable.classList.remove('is-dragging');
                            draggable.style.opacity = '1';
                            
                            // Determine which zone it was dropped in
                            var container = draggable.closest('.dnd-tier-zone');
                            var rank = '';
                            if(container) {
                                // Find the rank from the sibling label
                                var label = container.parentElement.querySelector('.dnd-tier-label');
                                if(label) rank = label.innerText.trim();
                            }
                            
                            // Update the hidden input
                            var hiddenInput = document.getElementById('input-' + itemId);
                            if(hiddenInput) hiddenInput.value = rank;
                        }
                        draggingElementId = null;
                        if(scrollInterval) clearInterval(scrollInterval);
                    }
                });
            </script>

            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn-submit">Guardar Cambios de la Tier List</button>
            </div>
        </form>
    </main>

    @include('partials.footer')
</body>

</html>
