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
                
                @forelse($strategies as $strategy)
                <div class="strategy-card" style="margin-bottom: 30px; position: relative;">
                    <h3>{{ $strategy->title }}</h3>
                    <p>{{ $strategy->description }}</p>
                    
                    @if($strategy->build_type)
                    <div style="margin-top: 15px; border-top: 1px solid #333; padding-top: 15px;">
                        <h4 style="color: #ffcf00; font-size: 1.1em; margin-bottom: 10px;">🛠️ Builds recomendadas por la comunidad:</h4>
                        @php $topBuilds = $strategy->top_builds; @endphp
                        @if($topBuilds->count() > 0)
                            <div style="display: grid; gap: 10px;">
                                @foreach($topBuilds as $build)
                                    <div style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border-left: 3px solid #e94560;">
                                        <div>
                                            <a href="{{ route('builds.show', $build->id) }}" style="color: #fff; text-decoration: none; font-weight: bold; transition: color 0.2s; font-size: 1.1em;" onmouseover="this.style.color='#ffcf00'" onmouseout="this.style.color='#fff'">{{ $build->name }}</a>
                                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 5px;">
                                                <img src="{{ $build->user && $build->user->avatar ? (str_starts_with($build->user->avatar, 'http') ? $build->user->avatar : asset('storage/avatars/' . $build->user->avatar)) : asset('images/default-avatar.png') }}" alt="Avatar" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; border: 1px solid #444;">
                                                <a href="{{ $build->user ? url('/perfil/' . $build->user->id) : '#' }}" style="color: #aaa; text-decoration: none; font-size: 0.9em; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">{{ $build->user->username ?? 'Anónimo' }}</a>
                                                <span style="color: #ffcf00; font-size: 0.9em; margin-left: 10px;">⭐ {{ number_format($build->rating, 1) }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('builds.show', $build->id) }}" class="btn-secondary" style="font-size: 0.8em; padding: 6px 12px; border-radius: 4px; background: transparent; color: #fff; border: 1px solid #e94560; text-decoration: none; transition: 0.2s;" onmouseover="this.style.background='#e94560'" onmouseout="this.style.background='transparent'">Ver →</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background: rgba(26, 26, 26, 0.5); padding: 15px; border-radius: 6px; text-align: center; border: 1px dashed #444;">
                                <p style="color: #aaa; font-style: italic; margin-bottom: 10px;">¿Tienes una build para esta estrategia? ¡Publícala!</p>
                                <a href="{{ route('builds.create') }}" class="btn-primary" style="display: inline-block; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.9em;">+ Crear Build</a>
                            </div>
                        @endif
                    </div>
                    @endif

                    <div style="margin-top: 20px; background: #232333; padding: 15px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: bold; color: #aaa;">¿Sigue siendo Meta?</span>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="color: #1da1f2; font-weight: bold; font-size: 1.2em;" id="confidence-{{ $strategy->id }}">
                                {{ $strategy->confidence_percentage !== null ? $strategy->confidence_percentage . '% Confianza' : 'Sin votos aún' }}
                            </span>
                            @auth
                                <div style="display: flex; gap: 5px;">
                                    <button onclick="voteStrategy({{ $strategy->id }}, 1)" style="background: transparent; border: 1px solid #2e7d32; color: #a5d6a7; padding: 5px 15px; border-radius: 4px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='rgba(46,125,50,0.2)'" onmouseout="this.style.background='transparent'">Sí</button>
                                    <button onclick="voteStrategy({{ $strategy->id }}, 0)" style="background: transparent; border: 1px solid #d32f2f; color: #ef9a9a; padding: 5px 15px; border-radius: 4px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='rgba(211,47,47,0.2)'" onmouseout="this.style.background='transparent'">No</button>
                                </div>
                            @else
                                <span style="font-size: 0.85em; color: #888;">(<a href="{{ route('login') }}" style="color: #e94560;">Inicia sesión</a> para votar)</span>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                    <p style="color: #888; font-style: italic;">Aún no se han definido estrategias en la base de datos.</p>
                @endforelse
            </section>

            <aside class="meta-sidebar">
                
                <!-- Tendencias de Personajes -->
                @if(isset($trends) && count($trends) > 0)
                <section style="background: #1a1a24; padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <h2 style="color: #1da1f2; margin-bottom: 20px; font-size: 1.1em; text-transform: uppercase; letter-spacing: 1px; text-align: center;">📊 Tendencias de Uso<br><span style="font-size: 0.8em; color: #888;">(Últimos 7 días)</span></h2>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($trends as $index => $trend)
                            <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; {{ $index === 0 ? 'border-left: 3px solid #ff416c; background: rgba(255, 65, 108, 0.05);' : 'border-left: 3px solid #444;' }}">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: {{ $index === 0 ? '2px solid #ff416c; box-shadow: 0 0 10px rgba(255, 65, 108, 0.5);' : '1px solid #444;' }}">
                                        <img src="{{ asset('storage/' . $trend['character']->image_path) }}" alt="{{ $trend['character']->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <span style="color: #fff; font-size: 0.9em; font-weight: bold;">{{ Str::limit($trend['character']->name, 15) }}</span>
                                </div>
                                <span style="color: {{ $index === 0 ? '#ffcf00' : '#aaa' }}; font-weight: bold; font-size: 0.95em;">{{ $trend['percentage'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                @if(isset($latestPatch) && $latestPatch)
                    <h2>Cambios Clave - {{ Str::limit($latestPatch->title, 40) }}</h2>
                    <div style="background: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 6px; border-left: 3px solid #00f0ff; margin-bottom: 20px;">
                        <p style="color: #ddd; font-size: 0.95em; line-height: 1.6; margin-bottom: 15px;">
                            {!! nl2br(e(Str::limit(strip_tags($latestPatch->content), 250))) !!}
                        </p>
                        <a href="{{ route('info.news') }}" style="color: #00f0ff; text-decoration: none; font-size: 0.9em; font-weight: bold; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#00f0ff'">Ver notas completas en Novedades →</a>
                    </div>
                @else
                    <h2>Cambios Clave del Parche</h2>
                    <div style="background: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 6px; border-left: 3px solid #444; margin-bottom: 20px;">
                        <p style="color: #888; font-style: italic; font-size: 0.9em;">Aún no se han sincronizado notas de parche recientes.</p>
                        <a href="{{ route('info.news') }}" style="color: #888; text-decoration: none; font-size: 0.9em;">Ir a Novedades →</a>
                    </div>
                @endif

                <h2>Personajes en la Cima</h2>
                <div class="top-characters">
                    @forelse($topCharacters as $index => $character)
                        <div style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 6px; border-left: 3px solid #ff00ff; margin-bottom: 15px; position: relative;">
                            <span style="position: absolute; top: -10px; right: 10px; background: #ffcf00; color: #000; font-size: 0.7em; font-weight: bold; padding: 3px 8px; border-radius: 12px; box-shadow: 0 0 10px rgba(255, 207, 0, 0.5);">
                                #{{ $index + 1 }} Popular
                            </span>
                            <p style="margin: 0; line-height: 1.5; color: #ddd; font-size: 0.95em;">
                                @if(strtolower($character->role) === 'tanque' || strtolower($character->role) === 'tank')
                                    🛡️
                                @elseif(strtolower($character->role) === 'support' || strtolower($character->role) === 'soporte')
                                    ⚕️
                                @else
                                    👑
                                @endif
                                <strong style="color: #fff; font-size: 1.1em;">{{ $character->name }}:</strong> 
                                {{ Str::limit($character->description, 100, '...') }} 
                                <br>
                                <a href="{{ route('wiki.index') }}" style="color: #00f0ff; text-decoration: none; font-size: 0.85em; transition: 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#00f0ff'">(Ver guía en Wiki)</a>
                            </p>
                        </div>
                    @empty
                        <div style="background: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 6px; border-left: 3px solid #ffcf00; margin-bottom: 20px; animation: pulse 2s infinite;">
                            <p style="color: #ffcf00; font-style: italic; font-weight: bold; margin: 0;">¡Sé el primero en crear una build para este personaje!</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('wiki.index') }}" class="btn-tierlist-cta">Ver todos los personajes en la Wiki →</a>
            </aside>
            <style>
                @keyframes pulse {
                    0% { box-shadow: 0 0 0 0 rgba(255, 207, 0, 0.4); }
                    70% { box-shadow: 0 0 0 10px rgba(255, 207, 0, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(255, 207, 0, 0); }
                }
            </style>

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
