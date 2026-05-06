<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Actual | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/meta.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>

<body>

    @include('partials.header')

    <main class="main-content-meta">

        <h1 class="page-title">🧠 Análisis de la Meta Actual (Parche 3.1)</h1>

        <p class="intro-text-meta">
            La Meta de MEGABONK se define por los estilos de juego más efectivos en los niveles de dificultad más altos
            (Bonk +8 y superior). Esta es nuestra evaluación actual, enfocada en daño sostenido y supervivencia extrema.
        </p>

        <div class="meta-section-container">
            


            <section class="meta-strategies">
                <h2>Estrategias Dominantes</h2>
                
                @forelse($estrategias as $estrategia)
                <div class="strategy-card strategy-card-wrapper">
                    <h3>{{ $estrategia->title }}</h3>
                    <p>{{ $estrategia->description }}</p>
                    
                    @if($estrategia->build_type)
                    <div class="builds-container">
                        <h4 class="builds-title">🛠️ Builds recomendadas por la comunidad:</h4>
                        @php $topBuilds = $estrategia->top_builds; @endphp
                        @if($topBuilds->count() > 0)
                            <div class="build-list-grid">
                                @foreach($topBuilds as $build)
                                    <div class="build-card">
                                        <div>
                                            <a href="{{ route('builds.show', $build->id) }}" class="build-card-link">{{ $build->name }}</a>
                                            <div class="build-user-info">
                                                <img src="{{ $build->user && $build->user->avatar ? (str_starts_with($build->user->avatar, 'http') ? $build->user->avatar : asset('storage/avatars/' . $build->user->avatar)) : asset('images/default-avatar.png') }}" alt="Avatar" class="build-avatar">
                                                <a href="{{ $build->user ? url('/perfil/' . $build->user->id) : '#' }}" class="build-username">{{ $build->user->username ?? 'Anónimo' }}</a>
                                                <span class="build-rating">⭐ {{ number_format($build->rating, 1) }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('builds.show', $build->id) }}" class="btn-secondary btn-secondary-custom">Ver →</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-builds-msg">
                                <p class="empty-builds-text">¿Tienes una build para esta estrategia? ¡Publícala!</p>
                                <a href="{{ route('builds.create') }}" class="btn-primary btn-primary-inline">+ Crear Build</a>
                            </div>
                        @endif
                    </div>
                    @endif

                    <div class="meta-status-container">
                        <span class="meta-status-label">¿Sigue siendo Meta?</span>
                        <div class="meta-status-actions">
                            <span class="meta-confidence-score" id="confidence-{{ $estrategia->id }}">
                                {{ $estrategia->confidence_percentage !== null ? $estrategia->confidence_percentage . '% Confianza' : 'Sin votos aún' }}
                            </span>
                            @auth
                                <div class="meta-vote-buttons">
                                    <button onclick="voteStrategy({{ $estrategia->id }}, 1)" class="btn-vote-yes">Sí</button>
                                    <button onclick="voteStrategy({{ $estrategia->id }}, 0)" class="btn-vote-no">No</button>
                                </div>
                            @else
                                <span class="vote-login-prompt">(<a href="{{ route('login') }}" class="vote-login-link">Inicia sesión</a> para votar)</span>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                    <p class="empty-strategies-msg">Aún no se han definido estrategias en la base de datos.</p>
                @endforelse
            </section>

            <aside class="meta-sidebar">
                
                <!-- Tendencias de Personajes -->
                @if(isset($tendencias) && count($tendencias) > 0)
                <section class="trends-section">
                    <h2 class="trends-title">📊 Tendencias de Uso<br><span class="trends-subtitle">(Últimos 7 días)</span></h2>
                    <div class="trends-list">
                        @foreach($tendencias as $index => $tendencia)
                            <div class="trend-card {{ $index === 0 ? 'trend-card-first' : '' }}">
                                <div class="trend-character-info">
                                    <div class="trend-character-avatar {{ $index === 0 ? 'trend-avatar-first' : '' }}">
                                        <img src="{{ asset('storage/' . $tendencia['character']->image_path) }}" alt="{{ $tendencia['character']->name }}" class="trend-character-img">
                                    </div>
                                    <span class="trend-character-name">{{ Str::limit($tendencia['character']->name, 15) }}</span>
                                </div>
                                <span class="trend-percentage {{ $index === 0 ? 'trend-percentage-first' : '' }}">{{ $tendencia['percentage'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                @if(isset($ultimoParche) && $ultimoParche)
                    <h2>Cambios Clave - {{ Str::limit($ultimoParche->title, 40) }}</h2>
                    <div class="patch-notes-container">
                        <p class="patch-notes-content">
                            {!! nl2br(e(Str::limit(strip_tags($ultimoParche->content), 250))) !!}
                        </p>
                        <a href="{{ route('info.news') }}" class="patch-notes-link">Ver notas completas en Novedades →</a>
                    </div>
                @else
                    <h2>Cambios Clave del Parche</h2>
                    <div class="empty-patch-container">
                        <p class="empty-patch-msg">Aún no se han sincronizado notas de parche recientes.</p>
                        <a href="{{ route('info.news') }}" class="empty-patch-link">Ir a Novedades →</a>
                    </div>
                @endif

                <h2>Personajes en la Cima</h2>
                <div class="top-characters">
                    @forelse($topPersonajes as $index => $personaje)
                        <div class="top-character-card">
                            <span class="top-character-badge">
                                #{{ $index + 1 }} Popular
                            </span>
                            <p class="top-character-desc">
                                @if(strtolower($personaje->role) === 'tanque' || strtolower($personaje->role) === 'tank')
                                    🛡️
                                @elseif(strtolower($personaje->role) === 'support' || strtolower($personaje->role) === 'soporte')
                                    ⚕️
                                @else
                                    👑
                                @endif
                                <strong class="top-character-name">{{ $personaje->name }}:</strong> 
                                {{ Str::limit($personaje->description, 100, '...') }} 
                                <br>
                                <a href="{{ route('wiki.index') }}" class="top-character-link">(Ver guía en Wiki)</a>
                            </p>
                        </div>
                    @empty
                        <div class="empty-top-character-msg">
                            <p class="empty-top-character-text">¡Sé el primero en crear una build para este personaje!</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('wiki.index') }}" class="btn-tierlist-cta">Ver todos los personajes en la Wiki →</a>
            </aside>

        </div>
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
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>

    <script>
        function voteStrategy(strategyId, isMeta) {
            fetch(`/meta-strategies/${strategyId}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ is_meta: isMeta })
            })
            .then(response => {
                if(response.status === 401) {
                    alert('Debes iniciar sesión para votar.');
                    throw new Error('Unauthenticated');
                }
                return response.json();
            })
            .then(data => {
                if(data && data.success) {
                    document.getElementById(`confidence-${strategyId}`).innerText = data.confidence + '% Confianza';
                }
            })
            .catch(error => console.error('Error voting:', error));
        }
    </script>
</body>

</html>
