<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier List | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
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

        <div class="tierlist-container">
            <table>
                <thead>
                    <tr>
                        <th class="tier-rank">RANGO</th>
                        <th>UNIDADES DESTACADAS</th>
                        <th>DESCRIPCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rankDescriptions = [
                            'S' => 'Dominantes en el meta. Obligatorios para récords de puntuación alta y desafíos extremos.',
                            'A' => 'Excelentes y versátiles. Pueden limpiar el contenido más difícil, pero requieren una sinergia de build específica.',
                            'B' => 'Viables y funcionales. Buenos picks para la mayoría del contenido, pero se quedan atrás en los desafíos de nivel superior.',
                            'C' => 'Nicho o débiles. Solo útiles en composiciones de equipo muy específicas o para principiantes.'
                        ];
                        // Sort ranks manually if needed, but assuming S, A, B, C
                        $ranksOrder = ['S', 'A', 'B', 'C'];
                    @endphp

                    @foreach($ranksOrder as $rank)
                        @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                            <tr class="tier-{{ strtolower($rank) }}">
                                <td class="tier-rank">{{ $rank }}</td>
                                <td>
                                    <div class="tier-items-list"
                                        style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                                        @foreach($itemsByRank[$rank] as $item)
                                            <div class="tier-item" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                                                style="display: flex; flex-direction: column; align-items: center; width: 80px; text-align: center;">
                                                @if($item->image_path)
                                                    <img src="{{ asset('images/' . $item->image_path) }}" alt="{{ $item->name }}"
                                                        title="{{ $item->name }}"
                                                        style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px;">
                                                @else
                                                    <!-- Fallback icon or text -->
                                                    <div
                                                        style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                                        {{ $item->name }}</div>
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

        @if(isset($pendingItems) && $pendingItems->count() > 0)
        <section class="meta-links">
            <h2>🧪 Laboratorio de la Comunidad (Ítems Pendientes)</h2>
            <p>Vota por el rango en el que crees que deberían estar estos ítems pendientes para ayudar a formarla.</p>
            <div class="tier-items-list" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 20px;">
                @foreach($pendingItems as $item)
                    <div class="tier-item pending-item" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                        style="display: flex; flex-direction: column; align-items: center; width: 100px; text-align: center; background: #2c2f33; padding: 10px; border-radius: 8px;">
                        
                        @if($item->image_path)
                            <img src="{{ asset('images/' . $item->image_path) }}" alt="{{ $item->name }}"
                                title="{{ $item->name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #fff;">
                        @else
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px;">{{ $item->name }}</div>
                        @endif
                        <span style="font-size: 0.8em; margin: 8px 0; line-height: 1.1;">{{ $item->name }}</span>
                        
                        <div class="rank-buttons" style="display: flex; gap: 4px; margin-bottom: 12px;">
                            <button class="btn-rank rank-s" onclick="voteRank('{{ $item->id }}', 'S')" title="Votar clase S">S</button>
                            <button class="btn-rank rank-a" onclick="voteRank('{{ $item->id }}', 'A')" title="Votar clase A">A</button>
                            <button class="btn-rank rank-b" onclick="voteRank('{{ $item->id }}', 'B')" title="Votar clase B">B</button>
                            <button class="btn-rank rank-c" onclick="voteRank('{{ $item->id }}', 'C')" title="Votar clase C">C</button>
                        </div>

                        <div style="font-size: 0.7rem; color: #aaa;">
                            Más votado: <strong id="most-voted-{{ $item->id }}" style="color: #ffcf00;">{{ $mostVotedRanks[$item->id] ?? 'Ninguno' }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <section class="meta-links">
            <h2>🔗 Analiza la Meta</h2>
            <p>Consulta las **Builds** de mayor rango o el **Leaderboard** para ver estas unidades en acción.</p>
            <div class="action-buttons-small">
                <a href="{{ route('builds.search') }}" class="btn btn-primary-link">🔎 Buscador de Builds</a>
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