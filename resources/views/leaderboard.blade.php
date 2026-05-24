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

    <!-- =======================
         HEADER GLOBAL
    ======================= -->
    @include('partials.header')

    <!-- =======================
         CONTENIDO PRINCIPAL
    ======================= -->
    <main class="main-content-leaderboard">

        <!-- Título -->
        <h1 class="page-title">🏆 Leaderboard Global</h1>
        <!-- =======================
             CONTROLES Y FILTROS
        ======================= -->
        <p style="font-size: 0.95em; color: #aaa; margin-top: 0; margin-bottom: 15px; text-align: center;">
            Clasificación basada en la puntuación más alta por Personaje.
        </p>


        <div class="leaderboard-controls"
            style="background: #1e1e2e; border: 1px solid #333; border-radius: 12px; padding: 15px 25px; display: flex; flex-direction: row; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">

            <form action="{{ route('leaderboard') }}" method="GET"
                style="display: flex; flex-direction: row; align-items: center; gap: 12px; margin: 0; flex-wrap: wrap; flex-grow: 1;">

                <div style="display: flex; align-items: center; gap: 6px;">
                    <span
                        style="color: #bc13fe; font-weight: bold; font-size: 0.95em; white-space: nowrap;">Personaje:</span>
                    <select id="filter-character" name="character" class="custom-select" onchange="this.form.dispatchEvent(new Event('submit', {bubbles:true, cancelable:true}))"
                        style="background: #111; color: #fff; border: 1px solid #444; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                        <option value="all" {{ $characterId == 'all' ? 'selected' : '' }}>Todos</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}" {{ $characterId == $char->id ? 'selected' : '' }}>
                                {{ $char->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #bc13fe; font-weight: bold; font-size: 0.95em; white-space: nowrap;">Min:</span>
                    <input type="text" id="filter-score-min" name="score_min" class="custom-select score-filter-input"
                        placeholder="Ej: 100.000" value="{{ request('score_min') }}"
                        style="width: 100px; background: #111; color: #fff; border: 1px solid #444; padding: 6px 10px; border-radius: 6px;">
                </div>

                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #bc13fe; font-weight: bold; font-size: 0.95em; white-space: nowrap;">Max:</span>
                    <input type="text" id="filter-score-max" name="score_max" class="custom-select score-filter-input"
                        placeholder="Ej: 999.999" value="{{ request('score_max') }}"
                        style="width: 100px; background: #111; color: #fff; border: 1px solid #444; padding: 6px 10px; border-radius: 6px;">
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin-left: 5px;">
                    <button type="submit"
                        style="background: #e94560; color: #fff; border: none; padding: 7px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 0.9em;">
                        Filtrar
                    </button>
                    <a href="{{ route('leaderboard') }}"
                        style="background: transparent; color: #fff; border: 1px solid #555; padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 0.9em; font-weight: bold; white-space: nowrap;">
                        Limpiar
                    </a>
                </div>
            </form>

            <div style="flex-shrink: 0;">
                @auth
                    <button onclick="document.getElementById('scoreModal').style.display='block'"
                        style="background: #ffcf00; color: #000; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 800; cursor: pointer; box-shadow: 0 0 10px rgba(255, 207, 0, 0.3); white-space: nowrap;">
                        🏆 SUBIR PUNTUACIÓN
                    </button>
                @else
                    <a href="{{ route('login') }}"
                        style="background: #333; color: #fff; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85em; font-weight: bold; white-space: nowrap;">
                        Inicia sesión para subir
                    </a>
                @endauth
            </div>
        </div>

        <script>
            function initLeaderboardScripts() {
                const scoreInputs = document.querySelectorAll('.score-filter-input');
                scoreInputs.forEach(input => {
                    if (input.dataset.lbInit) return; // evitar doble bind
                    input.dataset.lbInit = '1';
                    input.addEventListener('input', function (e) {
                        let value = this.value.replace(/\D/g, '');
                        if (value) {
                            this.value = parseInt(value, 10).toLocaleString('es-ES');
                        } else {
                            this.value = '';
                        }
                    });
                });
            }
            document.addEventListener('DOMContentLoaded', initLeaderboardScripts);
            document.addEventListener('megabonk:ajaxLoad', initLeaderboardScripts);
        </script>

        <!-- =======================
             TABLA DE CLASIFICACIÓN
        ======================= -->
        <div class="leaderboard-table-container">
            <table>
                <thead>
                    <tr>
                        <th class="rank-col">#</th>
                        <th>Jugador</th>
                        <th>Puntuación Final</th>
                        <th>Tiempo</th>
                        <th>Personaje</th>
                        <th>Build (Link)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $actualRank = 1; @endphp
                    @forelse($scores as $index => $score)
                        <tr class="{{ $score->status == 'pending' ? 'pending-row' : ($actualRank <= 3 ? 'top-3' : '') }}"
                            style="{{ $score->status == 'pending' ? 'opacity: 0.5; background: rgba(255,165,0,0.1);' : '' }}">
                            <td
                                class="rank-col {{ $score->status == 'approved' ? ($actualRank == 1 ? 'rank-gold' : ($actualRank == 2 ? 'rank-silver' : ($actualRank == 3 ? 'rank-bronze' : ''))) : '' }}">
                                @if($score->status == 'pending')
                                    ⏳
                                @else
                                    {{ $actualRank++ }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('profile.public', $score->user->id) }}"
                                    style="color: #fff; text-decoration: none; font-weight: bold;">
                                    {{ $score->user->name ?? $score->user->username }}
                                </a>
                                @if($score->status == 'pending')
                                    <span
                                        style="font-size: 0.7em; background: orange; color: black; padding: 2px 5px; border-radius: 4px; margin-left: 5px;">Pendiente</span>
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
                            <td colspan="6" style="text-align: center; padding: 20px; color: #888;">No hay puntuaciones
                                registradas para esta categoría aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- =======================
             MODAL DE SUBIDA DE PUNTUACIÓN
        ======================= -->
        @auth
            <!-- Modal de Subida de Puntuación -->
            <style>
                .neon-input {
                    width: 100%;
                    padding: 8px;
                    background: #222;
                    border: 1px solid #444;
                    color: #fff;
                    border-radius: 4px;
                    outline: none;
                    transition: all 0.3s ease;
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
                    background: #444;
                    border: none;
                    color: #888;
                    font-weight: bold;
                    padding: 8px 15px;
                    border-radius: 4px;
                    cursor: not-allowed;
                    transition: all 0.3s ease;
                }

                .btn-submit-score.ready {
                    background: #00f0ff;
                    color: #000;
                    cursor: pointer;
                    box-shadow: 0 0 10px rgba(0, 240, 255, 0.6);
                    animation: readyPulse 1.5s infinite;
                }

                @keyframes readyPulse {
                    0% {
                        box-shadow: 0 0 10px rgba(0, 240, 255, 0.4);
                    }

                    50% {
                        box-shadow: 0 0 20px rgba(0, 240, 255, 0.8);
                    }

                    100% {
                        box-shadow: 0 0 10px rgba(0, 240, 255, 0.4);
                    }
                }
            </style>
            <div id="scoreModal"
                style="display: {{ (session('requires_confirmation') || $errors->any()) ? 'flex' : 'none' }}; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
                <div
                    style="background: #1a1a24; padding: 30px; border-radius: 10px; border: 2px solid #00f0ff; width: 90%; max-width: 500px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <h2 style="color: #00f0ff; margin-top: 0;">Subir Puntuación</h2>

                    {{-- Errores de validación de Laravel --}}
                    @if($errors->any())
                        <div style="background: rgba(255,0,60,0.15); border-left: 4px solid #ff003c; padding: 12px 16px; margin-bottom: 15px; border-radius: 4px;">
                            <h4 style="margin: 0 0 8px 0; color: #ff003c; font-size: 0.95em;">❌ Errores en el formulario:</h4>
                            <ul style="margin: 0; padding-left: 18px; color: #ff6b8a; font-size: 0.85em; line-height: 1.6;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif



                    @if(session('requires_confirmation'))
                        <div
                            style="background: rgba(255,165,0,0.2); border-left: 4px solid orange; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
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
                            <label style="display: block; margin-bottom: 5px; color: #ddd;">Personaje Usado:</label>
                            <select name="character_id" class="neon-input" data-required="true" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                                <option value="">-- Selecciona un Personaje --</option>
                                @foreach($characters as $char)
                                    <option value="{{ $char->id }}" {{ old('character_id') == $char->id ? 'selected' : '' }}>
                                        {{ $char->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; color: #ddd;">Puntuación Final (Ej:
                                9.875.120):</label>
                            <input type="text" id="points_formatted" class="neon-input" data-required="true" placeholder="0"
                                value="{{ old('points') ? number_format(old('points'), 0, '', '.') : '' }}" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                            <input type="hidden" id="points_raw" name="points" value="{{ old('points') }}">
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; color: #ddd;">Tiempo Final (Ej:
                                01:42:15):</label>
                            <input type="text" name="time" class="neon-input" data-required="true" placeholder="HH:MM:SS"
                                value="{{ old('time') }}" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord. Formato: HH:MM:SS.
                            </div>
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; margin-bottom: 5px; color: #ddd;">Build Usada (Opcional):</label>
                            <select name="build_id" class="neon-input" {{ session('requires_confirmation') ? 'readonly style=pointer-events:none;opacity:0.6;' : '' }}>
                                <option value="">Ninguna / No especificar</option>
                                @foreach(auth()->user()->builds as $build)
                                    <option value="{{ $build->id }}" {{ old('build_id') == $build->id ? 'selected' : '' }}>
                                        {{ Str::limit($build->name, 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" onclick="document.getElementById('scoreModal').style.display='none'"
                                style="background: transparent; border: 1px solid #888; color: #888; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Cancelar</button>
                            <button type="submit" id="btnSubmitScore"
                                class="btn-submit-score {{ session('requires_confirmation') ? 'ready' : '' }}" {{ session('requires_confirmation') ? '' : 'disabled' }}>
                                {{ session('requires_confirmation') ? 'CONFIRMAR NUEVO RÉCORD' : 'Enviar a Revisión' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- =======================
                                                 SCRIPT DE VALIDACIÓN FORMULARIO
                                            ======================= -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.getElementById('scoreForm');
                    const inputs = form.querySelectorAll('.neon-input[data-required="true"]');
                    const btnSubmit = document.getElementById('btnSubmitScore');
                    const pointsFormatted = document.getElementById('points_formatted');
                    const pointsRaw = document.getElementById('points_raw');

                    // Formateador de miles
                    pointsFormatted.addEventListener('input', function (e) {
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
                            if (errorDiv && errorDiv.classList.contains('custom-error')) errorDiv.style.display = 'none';
                        } else {
                            field.classList.remove('is-valid');
                            field.classList.add('is-invalid');
                            if (errorDiv && errorDiv.classList.contains('custom-error')) errorDiv.style.display = 'block';
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
                            input.addEventListener('input', function () {
                                validateField(this);
                                checkFormValidity();
                            });
                            input.addEventListener('change', function () {
                                validateField(this);
                                checkFormValidity();
                            });
                        }
                    });

                    // Validación al hacer submit
                    form.addEventListener('submit', function (e) {
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

    <!-- =======================
         FOOTER
    ======================= -->
    @include('partials.footer')

    @if(session('error_toast'))
    <div id="errorToast" class="custom-toast error-toast">
        <div class="toast-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="toast-text">
            {{ session('error_toast') }}
        </div>
        <button class="toast-close" onclick="closeToast()">&times;</button>
    </div>

    <style>
        .custom-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(220, 38, 38, 0.85); /* Fondo rojo translúcido */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-left: 6px solid #ff1a1a;
            color: #ffffff;
            padding: 18px 24px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideInFade 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
            opacity: 0;
            transform: translateX(50px);
        }
        
        .custom-toast.fade-out {
            animation: slideOutFade 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }

        .toast-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-text {
            font-size: 0.95rem;
            line-height: 1.4;
            font-weight: 500;
        }

        .toast-text strong {
            font-size: 1.05rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .toast-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.8rem;
            line-height: 1;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .toast-close:hover {
            color: #ffffff;
            transform: scale(1.1);
        }

        @keyframes slideInFade {
            0% {
                opacity: 0;
                transform: translateX(50px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutFade {
            0% {
                opacity: 1;
                transform: translateX(0);
            }
            100% {
                opacity: 0;
                transform: translateX(50px);
            }
        }
    </style>

    <script>
        function closeToast() {
            const toast = document.getElementById('errorToast');
            if (toast) {
                toast.classList.add('fade-out');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto cerrar después de 5 segundos
            setTimeout(() => {
                closeToast();
            }, 5000);
        });
    </script>
    @endif
</body>

</html>