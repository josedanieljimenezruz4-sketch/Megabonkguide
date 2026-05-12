<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier List | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>

<body>

    @include('partials.header')

    <main class="main-content-tierlist">

        <h1 class="page-title">Tier List: Ranking de la Meta Actual</h1>

        <p class="intro-text-tierlist">
            Nuestra Tier List se actualiza semanalmente con base en el rendimiento de los objetos y personajes en el
            **Leaderboard**.
            Las unidades se clasifican según su potencial máximo en los desafíos de mayor dificultad (Bonk +10).
            ¡Recuerda que la habilidad del jugador siempre es el factor más importante!
        </p>

        <!-- BLOQUE 1: TIER LIST OFICIAL (LA TABLA) -->
        <div class="tierlist-container" style="background: transparent; margin-bottom: 40px;">
            @php
                $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
                $colors = [
                    'S' => '#FFD700',
                    'A' => '#FF3131',
                    'B' => '#FF5E13',
                    'C' => '#FFF01F',
                    'D' => '#39FF14',
                    'E' => '#00FFEF',
                    'F' => '#6D6D6D'
                ];
            @endphp

            <div style="display: flex; flex-direction: column; gap: 4px; border-radius: 12px; overflow: hidden;">
                @foreach($ranksOrder as $rank)
                    <div class="tier-rank-row" style="display: flex; min-height: 80px; border-radius: 8px; overflow: hidden; box-shadow: 0 0 12px {{ $colors[$rank] }}22;">
                        <div style="display: flex; align-items: center; justify-content: center; width: 80px; background: linear-gradient(135deg, {{ $colors[$rank] }}, {{ $colors[$rank] }}cc); color: #000; font-size: 2.8em; font-weight: 900; flex-shrink: 0; font-family: 'Inter', 'Segoe UI', sans-serif; letter-spacing: -2px; box-shadow: 15px 0 35px {{ $colors[$rank] }}44, 0 0 25px {{ $colors[$rank] }}55; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            {{ $rank }}
                        </div>
                        <div style="flex-grow: 1; padding: 8px; display: flex; flex-wrap: wrap; gap: 4px; background: rgba(20,20,25,0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); align-items: center; align-content: center; border-left: 1px solid {{ $colors[$rank] }}22;">
                            @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                                @foreach($itemsByRank[$rank]->sortBy('name') as $item)
                                    <div class="tier-item" data-tippy-content="<b>{{ $item->name }}</b><br/>{{ $item->description ?? '' }}" style="width: 60px; height: 60px; flex-shrink: 0;">
                                        @php
                                            $imageSrc = asset('images/' . $item->image_path);
                                            if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                                $imageSrc = asset('storage/' . $item->image_path);
                                            }
                                        @endphp
                                        <img src="{{ $item->image_path ? $imageSrc : asset('images/placeholder.png') }}" alt="{{ $item->name }}" title="{{ $item->name }}"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1); background: #222; transition: transform 0.2s;">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- BLOQUE 3: FILTROS Y EXPLORACIÓN -->
        <h2 class="section-title" style="text-align: center; margin-bottom: 20px;">🔍 Explorador de Ítems</h2>
        
        <div style="display: flex; flex-direction: column; gap: 15px; align-items: center; margin-bottom: 25px;">
            <!-- Buscador por nombre -->
            <input type="text" id="itemSearchInput" placeholder="Buscar por nombre... (ej. Espadón)" 
                style="width: 100%; max-width: 400px; padding: 10px 15px; border-radius: 20px; border: 1px solid #0ff; background: #111; color: #fff; outline: none; box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);">
            
            <!-- Botones de categoría -->
            <div class="tier-filters" style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;" id="categoryFilters">
                <button class="filter-btn active" data-filter="all" style="padding: 8px 15px; border-radius: 5px; background: #333; color: white; border: 1px solid #555; cursor: pointer; transition: 0.3s;">Todos</button>
                <button class="filter-btn" data-filter="personaje" style="padding: 8px 15px; border-radius: 5px; background: #111; color: #aaa; border: 1px solid #444; cursor: pointer; transition: 0.3s;">Personajes</button>
                <button class="filter-btn" data-filter="arma" style="padding: 8px 15px; border-radius: 5px; background: #111; color: #aaa; border: 1px solid #444; cursor: pointer; transition: 0.3s;">Armas</button>
                <button class="filter-btn" data-filter="tomo" style="padding: 8px 15px; border-radius: 5px; background: #111; color: #aaa; border: 1px solid #444; cursor: pointer; transition: 0.3s;">Tomos</button>
            </div>
        </div>

        @if(isset($allItems) && $allItems->count() > 0)
        <div class="all-items-grid" id="allItemsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 15px; margin-bottom: 40px; background: transparent;">
            @foreach($allItems->sortBy('name') as $item)
                <div class="tier-item js-filterable-item" data-category="{{ $item->type }}" data-name="{{ strtolower($item->name) }}" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                    style="display: flex; flex-direction: column; align-items: center; text-align: center; background: #222; padding: 10px; border-radius: 8px; border: 1px solid #333; transition: all 0.3s;">
                    @php
                        $imageSrcAll = asset('images/' . $item->image_path);
                        if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                            $imageSrcAll = asset('storage/' . $item->image_path);
                        }
                        $itemRank = $item->rank ?? '?';
                        $rankColor = $colors[$itemRank] ?? '#aaa';
                    @endphp
                    <div style="position: relative;">
                        <img src="{{ $item->image_path ? $imageSrcAll : asset('images/placeholder.png') }}" alt="{{ $item->name }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                            style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #111;">
                        <span style="position: absolute; top: -5px; right: -5px; background: #111; border: 1px solid {{ $rankColor }}; color: {{ $rankColor }}; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.65em; font-weight: bold; box-shadow: 0 0 5px {{ $rankColor }};">{{ $itemRank }}</span>
                    </div>
                    <span style="font-size: 0.8em; margin: 8px 0; line-height: 1.1; color: #ddd; height: 2.2em; display: flex; align-items: center; justify-content: center; width: 100%;">{{ $item->name }}</span>
                    
                    <div style="width: 100%;">
                        @auth
                            @php
                                $existingSuggestion = $userSuggestions[$item->id] ?? null;
                                $selectBorder = $existingSuggestion ? '1px solid #0f0' : '1px solid #444';
                                $selectColor = $existingSuggestion ? '#0f0' : '#aaa';
                                $selectGlow = $existingSuggestion ? 'box-shadow: 0 0 6px rgba(0,255,0,0.3);' : '';
                            @endphp
                            <select class="tier-suggestion-select" onchange="if(this.value) voteRank('{{ $item->id }}', this.value, this)" style="background: #111; color: {{ $selectColor }}; border: {{ $selectBorder }}; border-radius: 4px; font-size: 0.75em; padding: 3px; cursor: pointer; outline: none; width: 100%; {{ $selectGlow }}">
                                <option value="">{{ $existingSuggestion ? 'Cambiar...' : 'Sugerir Tier...' }}</option>
                                @foreach(['S','A','B','C','D','E','F'] as $r)
                                    <option value="{{ $r }}" {{ $existingSuggestion === $r ? 'selected' : '' }}>{{ $r }}{{ $existingSuggestion === $r ? ' ✓' : '' }}</option>
                                @endforeach
                            </select>
                        @else
                            <button onclick="window.location.href='{{ route('login') }}'" style="background: #111; color: #aaa; border: 1px solid #444; border-radius: 4px; font-size: 0.75em; padding: 3px; cursor: pointer; outline: none; width: 100%;">Sugerir Tier...</button>
                        @endauth
                    </div>
                </div>
            @endforeach
            <!-- Elemento a mostrar si no hay resultados -->
            <div id="noResultsMsg" style="display: none; grid-column: 1 / -1; text-align: center; color: #aaa; padding: 20px;">
                No se encontraron ítems que coincidan con tu búsqueda.
            </div>
        </div>
        @endif

        <!-- BLOQUE 4: SECCIÓN COMUNIDAD -->
        <section class="meta-links" style="margin-top: 40px; margin-bottom: 40px; border: 1px solid #444; border-radius: 10px; padding: 20px; background: #1a1a1a;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
                <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">👥 Feed de la Comunidad</h2>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('community-tierlists.index') }}" class="btn btn-secondary-link" style="padding: 8px 15px; font-size: 0.9em; background: transparent; border: 1px solid #aaa; color: #aaa;">Ver Todas</a>
                    <a href="{{ route('community-tierlists.create') }}" class="btn btn-primary-link" style="padding: 8px 15px; font-size: 0.9em; background: #ff4757; color: white;">+ Crear tu Tier List</a>
                </div>
            </div>
            
            @if(isset($recentCommunityTierLists) && $recentCommunityTierLists->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
                    @foreach($recentCommunityTierLists as $ctl)
                        <a href="{{ route('community-tierlists.show', $ctl->id) }}" style="text-decoration: none; color: inherit;">
                            <div style="background: #2c2f33; padding: 15px; border-radius: 8px; border-left: 4px solid #ffcf00; transition: transform 0.2s, background 0.2s; cursor: pointer;">
                                <h3 style="margin: 0 0 5px 0; font-size: 1.1em; color: #fff;">{{ $ctl->titulo }}</h3>
                                <div style="display: flex; justify-content: space-between; font-size: 0.85em; color: #aaa; margin-bottom: 12px;">
                                    <span style="display: flex; align-items: center; gap: 4px;">Por: 
                                        <object><a href="{{ $ctl->user ? url('/perfil/' . $ctl->user->id) : '#' }}" style="color: #ffcf00; font-weight: bold; text-decoration: none;">
                                            {{ $ctl->user->username ?? 'Anónimo' }}
                                        </a></object>
                                        @if($ctl->user && $ctl->user->is_admin)
                                            <span style="color: #1da1f2; margin-left: 2px;" data-tippy-content="Tier List Oficial de Megabonk Guide">☑️</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @php
                                        $miniRanks = ['S' => '#ff7f7f', 'A' => '#ffbf7f', 'B' => '#ffff7f', 'C' => '#bfff7f', 'D' => '#7fffb2', 'E' => '#7fffff', 'F' => '#aaaaaa'];
                                    @endphp
                                    @foreach(['S', 'A', 'B', 'C', 'D', 'E', 'F'] as $r)
                                        @php
                                            $itemsInRank = $ctl->rows->where('rank', $r);
                                        @endphp
                                        @if($itemsInRank->count() > 0)
                                            <div style="display: flex; align-items: center; background: #1a1a1a; border-radius: 4px; overflow: hidden; height: 24px;">
                                                <div style="background: {{ $miniRanks[$r] }}; color: #000; font-weight: bold; width: 24px; text-align: center; line-height: 24px; font-size: 0.8em; flex-shrink: 0;">{{ $r }}</div>
                                                <div style="display: flex; gap: 2px; padding-left: 4px; overflow: hidden;">
                                                    @foreach($itemsInRank->take(8) as $row)
                                                        @if($row->item)
                                                            @php
                                                                $imgSrc = asset('images/' . $row->item->image_path);
                                                                if (\Illuminate\Support\Str::startsWith($row->item->image_path, 'items/')) $imgSrc = asset('storage/' . $row->item->image_path);
                                                            @endphp
                                                            <img src="{{ $row->item->image_path ? $imgSrc : asset('images/placeholder.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';" style="width: 20px; height: 20px; object-fit: contain; border-radius: 2px; background: #222;">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: #aaa; padding: 20px 0;">
                    Aún no hay Tier Lists creadas por la comunidad. ¡Sé el primero!
                </p>
            @endif
        </section>



    </main>

    <footer class="main-footer">
        <div class="footer-sections">
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                    <li><a href="{{ route('meta') }}">META</a></li>
                    <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                </ul>
            </div>
            <div>
                <h3>Soporte</h3>
                <ul>
                    <li><a href="#">Contáctanos</a></li>
                    <li><a href="{{ route('comunity.suggestions') }}">Sugerencias</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div>
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-copy">
            &copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.
        </div>
    </footer>

    <!-- Scripts para Tippy.js y Votaciones AJAX -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[data-tippy-content]', {
                theme: 'tierlist',
                placement: 'top',
                arrow: true
            });

            // Lógica de Búsqueda y Filtrado
            const searchInput = document.getElementById('itemSearchInput');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const allItems = document.querySelectorAll('.js-filterable-item');
            const noResultsMsg = document.getElementById('noResultsMsg');
            
            let currentFilter = 'all';
            let currentSearch = '';

            function filterItems() {
                let visibleCount = 0;

                allItems.forEach(item => {
                    const itemName = item.getAttribute('data-name') || '';
                    const itemCat = item.getAttribute('data-category') || '';
                    
                    const matchesSearch = itemName.includes(currentSearch);
                    const matchesFilter = currentFilter === 'all' || itemCat === currentFilter;

                    if (matchesSearch && matchesFilter) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Mostrar mensaje de "sin resultados" en el grid principal
                if (noResultsMsg) {
                    const gridItems = document.querySelectorAll('#allItemsGrid .js-filterable-item');
                    let gridVisible = 0;
                    gridItems.forEach(i => { if(i.style.display !== 'none') gridVisible++; });
                    noResultsMsg.style.display = gridVisible === 0 ? 'block' : 'none';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    currentSearch = e.target.value.toLowerCase().trim();
                    filterItems();
                });
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // Modificar estilos del botón activo
                    filterBtns.forEach(b => {
                        b.classList.remove('active');
                        b.style.background = '#111';
                        b.style.color = '#aaa';
                    });
                    btn.classList.add('active');
                    btn.style.background = '#333';
                    btn.style.color = 'white';

                    currentFilter = btn.getAttribute('data-filter');
                    filterItems();
                });
            });
        });



        function voteRank(itemId, rank, selectEl) {
            fetch(`/items/${itemId}/vote-rank`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ rank: rank })
            })
            .then(res => {
                if(res.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    showVoteFeedback(selectEl || document.body, true);
                    // Actualizar estilo del select a verde (voto persistido)
                    if (selectEl && selectEl.tagName === 'SELECT') {
                        selectEl.style.border = '1px solid #0f0';
                        selectEl.style.color = '#0f0';
                        selectEl.style.boxShadow = '0 0 6px rgba(0,255,0,0.3)';
                    }
                } else {
                    showVoteFeedback(selectEl || document.body, false);
                }
            })
            .catch(error => console.error('Error sugiriendo tier:', error));
        }

        function showVoteFeedback(el, success) {
            const parent = el.closest('.tier-item') || el.parentElement;
            if (!parent) return;
            const badge = document.createElement('div');
            badge.textContent = success ? '✓' : '✗';
            badge.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(0);color:' + (success ? '#0f0' : '#f44') + ';font-size:2em;font-weight:900;text-shadow:0 0 15px ' + (success ? '#0f0' : '#f44') + ';z-index:10;pointer-events:none;transition:all 0.3s ease;';
            parent.style.position = 'relative';
            parent.appendChild(badge);
            requestAnimationFrame(() => { badge.style.transform = 'translate(-50%,-50%) scale(1)'; badge.style.opacity = '1'; });
            setTimeout(() => { badge.style.opacity = '0'; badge.style.transform = 'translate(-50%,-70%) scale(0.5)'; }, 1500);
            setTimeout(() => { badge.remove(); }, 2000);
        }
    </script>
</body>

</html>
