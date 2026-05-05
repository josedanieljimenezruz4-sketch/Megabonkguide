<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard | Clasificación | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>

<body>

    @include('partials.header')

    <main class="main-content-leaderboard">

        <h1 class="page-title">🏆 Leaderboard Global: Bonk +10</h1>

        <div class="leaderboard-controls">
            <p>Clasificación basada en la puntuación más alta por Dificultad y Personaje.</p>
            
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <form action="{{ route('leaderboard') }}" method="GET" class="filter-options" style="display: flex; gap: 10px; align-items: center;">
                    <label for="filter-difficulty">Dificultad:</label>
                    <select id="filter-difficulty" name="difficulty" class="custom-select" onchange="this.form.submit()">
                        <option value="bonk10" {{ $difficulty == 'bonk10' ? 'selected' : '' }}>Bonk +10 (Actual)</option>
                        <option value="bonk8" {{ $difficulty == 'bonk8' ? 'selected' : '' }}>Bonk +8</option>
                        <option value="bonk5" {{ $difficulty == 'bonk5' ? 'selected' : '' }}>Bonk +5</option>
                    </select>
                    
                    <label for="filter-character">Personaje:</label>
                    <select id="filter-character" name="character" class="custom-select" onchange="this.form.submit()">
                        <option value="all" {{ $characterId == 'all' ? 'selected' : '' }}>Todos</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}" {{ $characterId == $char->id ? 'selected' : '' }}>{{ $char->name }}</option>
                        @endforeach
                    </select>
                </form>

                @auth
                    <button onclick="document.getElementById('scoreModal').style.display='block'" style="background: #ffcf00; color: #000; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; box-shadow: 0 0 10px rgba(255, 207, 0, 0.5);">🏆 SUBIR MI PUNTUACIÓN</button>
                @else
                    <a href="{{ route('login') }}" style="background: #444; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 0.9em;">Inicia sesión para subir puntuación</a>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(0, 255, 0, 0.2); border-left: 4px solid #0f0; padding: 10px; margin-bottom: 20px; color: #fff;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background: rgba(255, 0, 0, 0.2); border-left: 4px solid #f00; padding: 10px; margin-bottom: 20px; color: #fff;">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div style="background: rgba(255, 0, 0, 0.2); border-left: 4px solid #f00; padding: 10px; margin-bottom: 20px; color: #fff;">
                <ul style="margin:0; padding-left: 20px;">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="leaderboard-table-container">
            <table>
                <thead>
                    <tr>
                        <th class="rank-col">#</th>
                        <th>Jugador</th>
                        <th>Puntuación Final</th>
                        <th>Tiempo (min)</th>
                        <th>Personaje</th>
                        <th>Build (Link)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $actualRank = 1; @endphp
                    @forelse($scores as $index => $score)
                        <tr class="{{ $score->status == 'pending' ? 'pending-row' : ($actualRank <= 3 ? 'top-3' : '') }}" style="{{ $score->status == 'pending' ? 'opacity: 0.5; background: rgba(255,165,0,0.1);' : '' }}">
                            <td class="rank-col {{ $score->status == 'approved' ? ($actualRank == 1 ? 'rank-gold' : ($actualRank == 2 ? 'rank-silver' : ($actualRank == 3 ? 'rank-bronze' : ''))) : '' }}">
                                @if($score->status == 'pending')
                                    ⏳
                                @else
                                    {{ $actualRank++ }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('profile.public', $score->user->id) }}" style="color: #fff; text-decoration: none; font-weight: bold;">
                                    {{ $score->user->name ?? $score->user->username }}
                                </a>
                                @if($score->status == 'pending')
                                    <span style="font-size: 0.7em; background: orange; color: black; padding: 2px 5px; border-radius: 4px; margin-left: 5px;">Pendiente</span>
                                @endif
                            </td>
                            <td class="score-highlight">{{ number_format($score->points) }}</td>
                            <td>{{ $score->time }}</td>
                            <td>{{ $score->character->name ?? 'Desconocido' }}</td>
                            <td>
                                @if($score->build)
                                    <a href="{{ route('builds.show', $score->build_id) }}" class="build-link">Ver Build</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #888;">No hay puntuaciones registradas para esta categoría aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @auth
        <!-- Modal de Subida de Puntuación -->
        <style>
            .neon-input {
                width: 100%; padding: 8px; background: #222; border: 1px solid #444; color: #fff; border-radius: 4px; outline: none; transition: all 0.3s ease;
            }
            .neon-input:focus {
                border-color: #00f0ff;
                box-shadow: 0 0 8px rgba(0, 240, 255, 0.4);
            }
            .neon-input.is-invalid {
                border-color: #ff003c;
                box-shadow: 0 0 8px rgba(255, 0, 60, 0.4);
            }
            .neon-input.is-valid {
                border-color: #00ffaa;
                box-shadow: 0 0 8px rgba(0, 255, 170, 0.4);
            }
            .custom-error {
                color: #ff003c;
                font-size: 0.8em;
                margin-top: 5px;
                display: none;
                text-shadow: 0 0 5px rgba(255, 0, 60, 0.5);
            }
            .btn-submit-score {
                background: #444; border: none; color: #888; font-weight: bold; padding: 8px 15px; border-radius: 4px; cursor: not-allowed; transition: all 0.3s ease;
            }
            .btn-submit-score.ready {
                background: #00f0ff; color: #000; cursor: pointer;
                box-shadow: 0 0 10px rgba(0, 240, 255, 0.6);
                animation: readyPulse 1.5s infinite;
            }
            @keyframes readyPulse {
                0% { box-shadow: 0 0 10px rgba(0, 240, 255, 0.4); }
                50% { box-shadow: 0 0 20px rgba(0, 240, 255, 0.8); }
                100% { box-shadow: 0 0 10px rgba(0, 240, 255, 0.4); }
            }
        </style>
        <div id="scoreModal" style="display: {{ session('requires_confirmation') ? 'flex' : 'none' }}; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
            <div style="background: #1a1a24; padding: 30px; border-radius: 10px; border: 2px solid #00f0ff; width: 90%; max-width: 500px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <h2 style="color: #00f0ff; margin-top: 0;">Subir Puntuación</h2>
                
                @if(session('requires_confirmation'))
                    <div style="background: rgba(255,165,0,0.2); border-left: 4px solid orange; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                        <h4 style="margin: 0 0 10px 0; color: orange;">⚠️ Confirmar Nuevo Récord</h4>
                        <p style="margin: 0; color: #ddd; font-size: 0.9em;">{{ session('confirmation_msg') }}</p>
                    </div>
                @endif

                <form id="scoreForm" action="{{ route('leaderboard.store') }}" method="POST" novalidate>
                    @csrf
                    
                    @if(session('requires_confirmation'))
                        <input type="hidden" name="confirm_override" value="1">
                    @endif

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; color: #ddd;">Dificultad:</label>
                        <select name="difficulty" class="neon-input" data-required="true" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                            <option value="bonk10" {{ old('difficulty') == 'bonk10' ? 'selected' : '' }}>Bonk +10</option>
                            <option value="bonk8" {{ old('difficulty') == 'bonk8' ? 'selected' : '' }}>Bonk +8</option>
                            <option value="bonk5" {{ old('difficulty') == 'bonk5' ? 'selected' : '' }}>Bonk +5</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; color: #ddd;">Personaje Usado:</label>
                        <select name="character_id" class="neon-input" data-required="true" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                            <option value="">-- Selecciona un Personaje --</option>
                            @foreach($characters as $char)
                                <option value="{{ $char->id }}" {{ old('character_id') == $char->id ? 'selected' : '' }}>{{ $char->name }}</option>
                            @endforeach
                        </select>
                        <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; color: #ddd;">Puntuación Final (Ej: 9.875.120):</label>
                        <input type="text" id="points_formatted" class="neon-input" data-required="true" placeholder="0" value="{{ old('points') ? number_format(old('points'), 0, '', '.') : '' }}" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                        <input type="hidden" id="points_raw" name="points" value="{{ old('points') }}">
                        <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; color: #ddd;">Tiempo Final (Ej: 42:15):</label>
                        <input type="text" name="time" class="neon-input" data-required="true" placeholder="MM:SS" value="{{ old('time') }}" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                        <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; margin-bottom: 5px; color: #ddd;">Build Usada (Opcional):</label>
                        <select name="build_id" class="neon-input" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                            <option value="">Ninguna / No especificar</option>
                            @foreach(auth()->user()->builds as $build)
                                <option value="{{ $build->id }}" {{ old('build_id') == $build->id ? 'selected' : '' }}>{{ Str::limit($build->name, 40) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="document.getElementById('scoreModal').style.display='none'" style="background: transparent; border: 1px solid #888; color: #888; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Cancelar</button>
                        <button type="submit" id="btnSubmitScore" class="btn-submit-score {{ session('requires_confirmation') ? 'ready' : '' }}" {{ session('requires_confirmation') ? '' : 'disabled' }}>
                            {{ session('requires_confirmation') ? 'CONFIRMAR NUEVO RÉCORD' : 'Enviar a Revisión' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('scoreForm');
                const inputs = form.querySelectorAll('.neon-input[data-required="true"]');
                const btnSubmit = document.getElementById('btnSubmitScore');
                const pointsFormatted = document.getElementById('points_formatted');
                const pointsRaw = document.getElementById('points_raw');

                // Formateador de miles
                pointsFormatted.addEventListener('input', function(e) {
                    // Quitar todo lo que no sea número
                    let value = this.value.replace(/\D/g, '');
                    pointsRaw.value = value; // Guardar el valor real
                    // Formatear con puntos
                    if (value) {
                        this.value = parseInt(value, 10).toLocaleString('es-ES');
                    } else {
                        this.value = '';
                    }
                    validateField(this);
                    checkFormValidity();
                });

                function validateField(field) {
                    const errorDiv = field.nextElementSibling;
                    let isValid = false;

                    if (field.id === 'points_formatted') {
                        isValid = pointsRaw.value.trim() !== '';
                    } else {
                        isValid = field.value.trim() !== '';
                    }

                    if (isValid) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                        if(errorDiv && errorDiv.classList.contains('custom-error')) errorDiv.style.display = 'none';
                    } else {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                        if(errorDiv && errorDiv.classList.contains('custom-error')) errorDiv.style.display = 'block';
                    }
                    return isValid;
                }

                function checkFormValidity() {
                    let allValid = true;
                    inputs.forEach(input => {
                        if (input.id === 'points_formatted') {
                            if (pointsRaw.value.trim() === '') allValid = false;
                        } else if (input.value.trim() === '') {
                            allValid = false;
                        }
                    });

                    if (allValid) {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.add('ready');
                    } else {
                        btnSubmit.disabled = true;
                        btnSubmit.classList.remove('ready');
                    }
                }

                // Event Listeners para validación en tiempo real
                inputs.forEach(input => {
                    if (input.id !== 'points_formatted') {
                        input.addEventListener('input', function() {
                            validateField(this);
                            checkFormValidity();
                        });
                        input.addEventListener('change', function() {
                            validateField(this);
                            checkFormValidity();
                        });
                    }
                });

                // Validación al hacer submit
                form.addEventListener('submit', function(e) {
                    let isFormValid = true;
                    inputs.forEach(input => {
                        if (!validateField(input)) {
                            isFormValid = false;
                        }
                    });

                    if (!isFormValid) {
                        e.preventDefault(); // Evitar envío si hay errores
                    }
                });
            });
        </script>
        @endauth

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

</body>

</html>
