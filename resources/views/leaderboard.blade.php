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
        <p class="leaderboard-subtitle">
            Clasificación basada en la puntuación más alta por Personaje.
        </p>


        <div class="leaderboard-controls">

            <form action="{{ route('leaderboard') }}" method="GET" class="leaderboard-filter-form">

                <div class="filter-inline-group">
                    <span class="filter-label">Personaje:</span>
                    <select id="filter-character" name="character" class="custom-select filter-select" onchange="this.form.dispatchEvent(new Event('submit', {bubbles:true, cancelable:true}))">
                        <option value="all" {{ $characterId == 'all' ? 'selected' : '' }}>Todos</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}" {{ $characterId == $char->id ? 'selected' : '' }}>
                                {{ $char->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-inline-group">
                    <span class="filter-label">Min:</span>
                    <input type="text" id="filter-score-min" name="score_min" class="custom-select score-filter-input filter-input-score"
                        placeholder="Ej: 100.000" value="{{ request('score_min') }}">
                </div>

                <div class="filter-inline-group">
                    <span class="filter-label">Max:</span>
                    <input type="text" id="filter-score-max" name="score_max" class="custom-select score-filter-input filter-input-score"
                        placeholder="Ej: 999.999" value="{{ request('score_max') }}">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter-submit">
                        Filtrar
                    </button>
                    <a href="{{ route('leaderboard') }}" class="btn-filter-clear">
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="leaderboard-cta-container">
                @auth
                    <button onclick="document.getElementById('scoreModal').style.display='block'" class="btn-subir-puntuacion">
                        🏆 SUBIR PUNTUACIÓN
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-login-leaderboard">
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
                        <tr class="{{ $score->estatus == 'pending' ? 'pending-row pending-row-inline' : ($actualRank <= 3 ? 'top-3' : '') }}">
                            <td
                                class="rank-col {{ $score->estatus == 'approved' ? ($actualRank == 1 ? 'rank-gold' : ($actualRank == 2 ? 'rank-silver' : ($actualRank == 3 ? 'rank-bronze' : ''))) : '' }}">
                                @if($score->estatus == 'pending')
                                    ⏳
                                @else
                                    {{ $actualRank++ }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('profile.public', $score->user->id) }}" class="player-link">
                                    {{ $score->user->name ?? $score->user->username }}
                                </a>
                                @if($score->estatus == 'pending')
                                    <span class="badge-pending">Pendiente</span>
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
                            <td colspan="6" class="empty-row-message">No hay puntuaciones
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
            <div id="scoreModal" class="score-modal-overlay"
                style="display: {{ (session('requires_confirmation') || $errors->any()) ? 'flex' : 'none' }};">
                <div class="score-modal-content">
                    <h2 class="score-modal-title">Subir Puntuación</h2>

                    {{-- Errores de validación de Laravel --}}
                    @if($errors->any())
                        <div class="validation-errors-box">
                            <h4 class="validation-errors-title">❌ Errores en el formulario:</h4>
                            <ul class="validation-errors-list">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('requires_confirmation'))
                        <div class="confirmation-box">
                            <h4 class="confirmation-title">⚠️ Confirmar Nuevo Récord</h4>
                            <p class="confirmation-message">{{ session('confirmation_msg') }}</p>
                        </div>
                    @endif

                    <form id="scoreForm" action="{{ route('leaderboard.store') }}" method="POST" novalidate>
                        @csrf

                        @if(session('requires_confirmation'))
                            <input type="hidden" name="confirm_override" value="1">
                        @endif

                        <div class="modal-field-group">
                            <label class="modal-label">Personaje Usado:</label>
                            <select name="character_id" class="neon-input {{ session('requires_confirmation') ? 'modal-readonly' : '' }}" data-required="true">
                                <option value="">-- Selecciona un Personaje --</option>
                                @foreach($characters as $char)
                                    <option value="{{ $char->id }}" {{ old('character_id') == $char->id ? 'selected' : '' }}>
                                        {{ $char->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                        </div>
                        <div class="modal-field-group">
                            <label class="modal-label">Puntuación Final (Ej: 9.875.120):</label>
                            <input type="text" id="points_formatted" class="neon-input {{ session('requires_confirmation') ? 'modal-readonly' : '' }}" data-required="true" placeholder="0"
                                value="{{ old('points') ? number_format(old('points'), 0, '', '.') : '' }}">
                            <input type="hidden" id="points_raw" name="points" value="{{ old('points') }}">
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord.</div>
                        </div>
                        <div class="modal-field-group">
                            <label class="modal-label">Tiempo Final (Ej: 01:42:15):</label>
                            <input type="text" name="time" class="neon-input {{ session('requires_confirmation') ? 'modal-readonly' : '' }}" data-required="true" placeholder="HH:MM:SS"
                                value="{{ old('time') }}">
                            <div class="custom-error">Este campo es obligatorio para verificar tu récord. Formato: HH:MM:SS.
                            </div>
                        </div>
                        <div class="modal-field-group modal-field-group--last">
                            <label class="modal-label">Build Usada (Opcional):</label>
                            <select name="build_id" class="neon-input {{ session('requires_confirmation') ? 'modal-readonly' : '' }}">
                                <option value="">Ninguna / No especificar</option>
                                @foreach(auth()->user()->builds as $build)
                                    <option value="{{ $build->id }}" {{ old('build_id') == $build->id ? 'selected' : '' }}>
                                        {{ Str::limit($build->name, 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-actions">
                            <button type="button" onclick="document.getElementById('scoreModal').style.display='none'" class="btn-modal-cancel">Cancelar</button>
                            <button type="submit" id="btnSubmitScore"
                                class="btn-submit-score {{ session('requires_confirmation') ? 'ready' : '' }}" {{ session('requires_confirmation') ? '' : 'disabled' }}>
                                {{ session('requires_confirmation') ? 'CONFIRMAR NUEVO RÉCORD' : 'Enviar a Revisión' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- =======================
                 SCRIPT DE VALIDACIÓN FORMULARIO Y AJAX
            ======================= -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.getElementById('scoreForm');
                    const inputs = form.querySelectorAll('.neon-input[data-required="true"]');
                    const btnSubmit = document.getElementById('btnSubmitScore');
                    const pointsFormatted = document.getElementById('points_formatted');
                    const pointsRaw = document.getElementById('points_raw');

                    // Carga dinámica de builds filtradas por personaje seleccionado
                    const characterSelect = form.querySelector('select[name="character_id"]');
                    const buildSelect = form.querySelector('select[name="build_id"]');

                    if (characterSelect && buildSelect && !characterSelect.classList.contains('modal-readonly')) {
                        characterSelect.addEventListener('change', function() {
                            const charId = this.value;

                            if (!charId) {
                                buildSelect.innerHTML = '<option value="">Ninguna / No especificar</option>';
                                return;
                            }

                            buildSelect.innerHTML = '<option value="">Cargando builds...</option>';

                            fetch(`/leaderboard/user-builds/${charId}`)
                                .then(response => response.json())
                                .then(data => {
                                    buildSelect.innerHTML = '<option value="">Ninguna / No especificar</option>';
                                    data.forEach(build => {
                                        const option = document.createElement('option');
                                        option.value = build.id;
                                        option.textContent = build.name.length > 40 ? build.name.substring(0, 40) + '...' : build.name;
                                        buildSelect.appendChild(option);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error fetching builds:', error);
                                    buildSelect.innerHTML = '<option value="">Ninguna / No especificar</option>';
                                });
                        });
                    }

                    // Formateador de miles para el campo de puntos
                    pointsFormatted.addEventListener('input', function (e) {
                        let value = this.value.replace(/\D/g, '');
                        pointsRaw.value = value;
                        if (value) {
                            this.value = parseInt(value, 10).toLocaleString('es-ES');
                        } else {
                            this.value = '';
                        }
                        validateField(this);
                        checkFormValidity();
                    });

                    // Valida un campo individual y muestra/oculta el error visual
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

                    // Comprueba si todos los campos requeridos son válidos para activar el botón
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

                    // Validación en tiempo real para cada campo requerido
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

                    // Validación final al enviar el formulario
                    form.addEventListener('submit', function (e) {
                        let isFormValid = true;
                        inputs.forEach(input => {
                            if (!validateField(input)) {
                                isFormValid = false;
                            }
                        });

                        if (!isFormValid) {
                            e.preventDefault();
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