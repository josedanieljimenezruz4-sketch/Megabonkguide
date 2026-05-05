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


        <div class="tierlist-container">
            <table>
                <tbody>
                    @php
                        $rankDescriptions = [
                            'S' => 'Dominantes en el meta. Obligatorios para récords de puntuación alta y desafíos extremos.',
                            'A' => 'Excelentes y versátiles. Pueden limpiar el contenido más difícil, pero requieren una sinergia de build específica.',
                            'B' => 'Viables y funcionales. Buenos picks para la mayoría del contenido, pero se quedan atrás en los desafíos de nivel superior.',
                            'C' => 'Nicho o débiles. Solo útiles en composiciones de equipo muy específicas o para principiantes.'
                        ];
                        // Sort ranks manually if needed, but assuming S, A, B, C
                        $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
                    @endphp

                    @foreach($ranksOrder as $rank)
                        @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                            <tr class="tier-{{ strtolower($rank) }}">
                                <td class="tier-rank">{{ $rank }}</td>
                                <td>
                                    <div class="tier-items-list"
                                        style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                                        @foreach($itemsByRank[$rank]->sortByDesc('votes') as $item)
                                            <div class="tier-item" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                                                style="display: flex; flex-direction: column; align-items: center; width: 80px; text-align: center;">
                                                @php
                                                    $imageSrc = asset('images/' . $item->image_path);
                                                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                                        $imageSrc = asset('storage/' . $item->image_path);
                                                    }
                                                @endphp
                                                @if($item->image_path)
                                                    <img src="{{ $imageSrc }}" alt="{{ $item->name }}"
                                                        title="{{ $item->name }}"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                                        style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                                                @else
                                                    <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}"
                                                        title="{{ $item->name }}"
                                                        style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                                                @endif
                                                <span style="font-size: 0.8em; margin-top: 5px; line-height: 1.1;">{{ $item->name }}</span>
                                                <button class="vote-btn" onclick="voteItem('{{ $item->id }}')" title="Votar esta unidad">
                                                    🔥 <span id="vote-count-{{ $item->id }}">{{ $item->votes }}</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $rankDescriptions[$rank] ?? '' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

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

        <div class="tier-filters">
            <a href="{{ route('tierlist') }}" class="filter-btn filter-all {{ !$category ? 'active' : '' }}">Todos</a>
            <a href="{{ route('tierlist', ['category' => 'personaje']) }}"
                class="filter-btn filter-personajes {{ $category == 'personaje' ? 'active' : '' }}">Personajes</a>
            <a href="{{ route('tierlist', ['category' => 'arma']) }}"
                class="filter-btn filter-armas {{ $category == 'arma' ? 'active' : '' }}">Armas</a>
            <a href="{{ route('tierlist', ['category' => 'tomo']) }}"
                class="filter-btn filter-tomos {{ $category == 'tomo' ? 'active' : '' }}">Tomos</a>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ route('tierlist', ['category' => $category ?? null, 'sort' => 'popularity']) }}" class="btn btn-secondary-link" style="font-size: 0.85em; padding: 8px 15px;">
                🔥 Ordenar por Popularidad
            </a>
        </div>

        @if(isset($pendingItems) && $pendingItems->count() > 0)
        <section class="meta-links">
            <h2>🧪 Votos de Popularidad (Ítems Pendientes)</h2>
            <p>Dale tu voto (🔥) a los ítems que crees que deberíamos analizar y asignar pronto al meta oficial.</p>
            
            @if(auth()->check() && auth()->user()->is_admin)
            <div id="bulk-action-bar" style="display: none; background: rgba(255, 71, 87, 0.1); border: 1px dashed #ff4757; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center;">
                <span style="font-weight: bold; color: #ff4757; margin-right: 15px;">Acciones Masivas:</span>
                <select id="bulk-rank-select" style="padding: 8px; border-radius: 4px; background: #222; color: white; border: 1px solid #ff4757; outline: none; cursor: pointer;">
                    <option value="">Seleccionar Rango...</option>
                    <option value="S">Clase S</option>
                    <option value="A">Clase A</option>
                    <option value="B">Clase B</option>
                    <option value="C">Clase C</option>
                    <option value="D">Clase D</option>
                    <option value="E">Clase E</option>
                    <option value="F">Clase F</option>
                </select>
                <button class="btn btn-primary-link" style="padding: 8px 20px; font-size: 0.9em; margin-left: 10px; cursor: pointer; background-color: #ff4757; color: white;" onclick="submitBulkApprove()">Mover Seleccionados</button>
            </div>
            <div style="text-align: right; margin-top: 10px; width: 100%; max-width: 1000px; margin-inline: auto;">
                <label style="cursor: pointer; color: #ff4757; font-weight: bold; display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                    <input type="checkbox" id="select-all-pending" onchange="toggleAllPending()" style="transform: scale(1.3); cursor: pointer;"> Seleccionar todos
                </label>
            </div>
            @endif

            <div class="tier-items-list" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 20px;">
                @foreach($pendingItems as $item)
                    <div class="tier-item pending-item" id="pending-item-{{ $item->id }}" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                        style="position: relative; display: flex; flex-direction: column; align-items: center; width: 100px; text-align: center; background: #2c2f33; padding: 10px; border-radius: 8px; transition: all 0.3s ease; box-shadow: 0 0 0 2px transparent;">
                        
                        @if(auth()->check() && auth()->user()->is_admin)
                        <input type="checkbox" class="pending-item-checkbox" value="{{ $item->id }}" onchange="toggleItemSelection('{{ $item->id }}')" style="position: absolute; top: 8px; right: 8px; cursor: pointer; transform: scale(1.3); z-index: 10;">
                        @endif
                        
                        @php
                            $imageSrcP = asset('images/' . $item->image_path);
                            if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                $imageSrcP = asset('storage/' . $item->image_path);
                            }
                        @endphp
                        @if($item->image_path)
                            <img src="{{ $imageSrcP }}" alt="{{ $item->name }}"
                                title="{{ $item->name }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}"
                                title="{{ $item->name }}"
                                style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                        @endif
                        <span style="font-size: 0.8em; margin: 8px 0; line-height: 1.1;">{{ $item->name }}</span>
                        


                        <button class="vote-btn" onclick="voteItem('{{ $item->id }}')" title="Votar esta unidad para Popularidad" style="margin-bottom: 10px; margin-top: 5px; cursor: pointer; background: #1a1a1a; border: 1px solid #444; border-radius: 5px; padding: 4px 8px; color: white;">
                            🔥 <span id="vote-count-{{ $item->id }}">{{ $item->votes }}</span>
                        </button>


                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <section class="meta-links">
            <h2>🔗 Analiza la Meta</h2>
            <p>Consulta las **Builds** de mayor rango o el **Leaderboard** para ver estas unidades en acción.</p>
            <div class="action-buttons-small">
                <a href="{{ route('builds.index') }}" class="btn btn-primary-link">🔎 Buscador de Builds</a>
                <a href="{{ route('leaderboard') }}" class="btn btn-secondary-link">🏆 Ver Leaderboard</a>
            </div>
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
        });

        function voteItem(itemId) {
            fetch(`/items/${itemId}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if(res.status === 401) {
                    alert('Debes iniciar sesión para votar.');
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    document.getElementById('vote-count-' + itemId).innerText = data.votes;
                } else if (data && !data.success && data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error voting:', error));
        }

        function toggleItemSelection(itemId) {
            const itemDiv = document.getElementById('pending-item-' + itemId);
            const checkbox = itemDiv.querySelector('.pending-item-checkbox');
            if (checkbox.checked) {
                itemDiv.style.boxShadow = '0 0 0 2px #ff4757';
                itemDiv.style.background = 'rgba(255, 71, 87, 0.15)';
            } else {
                itemDiv.style.boxShadow = '0 0 0 2px transparent';
                itemDiv.style.background = '#2c2f33';
            }
            updateBulkActionBar();
        }

        function toggleAllPending() {
            const masterCheckbox = document.getElementById('select-all-pending');
            const checkboxes = document.querySelectorAll('.pending-item-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
                toggleItemSelection(cb.value);
            });
        }

        function updateBulkActionBar() {
            const checkboxes = document.querySelectorAll('.pending-item-checkbox:checked');
            const bulkActionBar = document.getElementById('bulk-action-bar');
            if (bulkActionBar) {
                bulkActionBar.style.display = checkboxes.length > 0 ? 'block' : 'none';
            }
        }

        function submitBulkApprove() {
            const checkedBoxes = document.querySelectorAll('.pending-item-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            const rank = document.getElementById('bulk-rank-select').value;
            if (!rank) {
                alert('Por favor selecciona un rango de destino.');
                return;
            }

            if (!confirm(`¿Estás seguro de mover estos ${checkedBoxes.length} ítems al rango ${rank}?`)) return;

            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            fetch(`/admin/items/bulk-approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: ids, rank: rank })
            })
            .then(res => {
                if(res.status === 401) {
                    alert('Debes iniciar sesión como administrador.');
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    alert(data.message);
                    location.reload();
                } else if (data && !data.success && data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error in bulk approve:', error));
        }

        function approveRank(itemId, rank) {
            if (!confirm('¿Estás seguro de que quieres aprobar permanentemente este ítem en el rango ' + rank + '?')) return;

            fetch(`/admin/items/${itemId}/approve-rank`, {
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
                    alert('Debes iniciar sesión como administrador.');
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    alert(data.message);
                    location.reload(); // Recargar para ver el cambio
                } else if (data && !data.success && data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error approving rank:', error));
        }

        function voteRank(itemId, rank) {
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
                    alert('Debes iniciar sesión para votar.');
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    document.getElementById('most-voted-' + itemId).innerText = data.most_voted_rank;
                } else if (data && !data.success && data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error voting rank:', error));
        }
    </script>
</body>

</html>
