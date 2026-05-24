<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Build | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}" type="image/x-icon">
    <!-- Fuentes modernas -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-glow: #B965F0;
            --secondary-glow: #ff416c;
            --bg-dark: #0f1016;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f0f0f0;
            --text-muted: #a0a0b0;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(185, 101, 240, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(255, 65, 108, 0.08), transparent 25%);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .premium-container {
            max-width: 850px;
            margin: 60px auto;
            padding: 40px;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .page-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 40px;
            background: linear-gradient(135deg, var(--primary-glow), var(--secondary-glow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0px 4px 20px rgba(185, 101, 240, 0.3);
        }

        .form-section {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid var(--glass-border);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            border-color: rgba(185, 101, 240, 0.3);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
            margin-top: 0;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .slot-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        select, input[type="text"], input[type="number"], textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }
        
        select option {
            background-color: #1a1a2e; /* Dark fallback for options list */
            color: #fff;
        }

        select:focus, input:focus {
            outline: none;
            border-color: var(--primary-glow);
            box-shadow: 0 0 15px rgba(185, 101, 240, 0.3);
            background-color: rgba(255, 255, 255, 0.08);
        }

        /* Styling disabled options to look clearly blocked out */
        select option:disabled {
            color: #555;
            background-color: #111;
        }

        .dynamic-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .dynamic-header h3 {
            margin: 0;
        }

        .dynamic-header .count-selector {
            width: 160px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-glow), var(--secondary-glow));
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 10px 25px rgba(255, 65, 108, 0.5);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #4ade80;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid rgba(40, 167, 69, 0.2);
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
        }
    </style>
</head>
<body>

    @include('partials.header')

    <main class="main-content" style="padding-top: 100px;">
        
        <!-- Alpine State: controls dynamic quantity of slots AND tracks selected values -->
        <div class="premium-container" 
             x-data="{ 
                armasCount: {{ $armasCountTotal }}, 
                tomosCount: {{ $tomosCountTotal }},
                selectedArmas: {{ json_encode((object)$selectedArmas) }},
                selectedTomos: {{ json_encode((object)$selectedTomos) }},
                selectedItems: {{ json_encode((object)$selectedItems) }}
             }">
            
            <h1 class="page-title">EDITAR BUILD</h1>
            
            @if(session('success'))
                <div class="alert-success fade-in">
                    ✨ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('builds.update', $build->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- DATOS GENERALES -->
                <div class="form-section fade-in" style="animation-delay: 0.1s;">
                    <div class="slot-group" style="grid-template-columns: 1fr;">
                        <div>
                            <label>Título de la Build</label>
                            <input type="text" name="name" value="{{ $build->name }}" required placeholder="Ej: Fuego Inmortal..." maxlength="255">
                            
                            <label style="margin-top: 20px;">DESCRIPCIÓN / ESTRATEGIA (OPCIONAL)</label>
                            <textarea name="description" rows="5" placeholder="Explica cómo se juega, el orden de compra, combos, etc.">{{ old('description', $build->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section fade-in" style="animation-delay: 0.2s;">
                    <h3 class="section-title">🎭 Identidad Base</h3>
                    <div class="slot-group" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                        <div>
                            <label>Rol Principal</label>
                            <select name="type">
                                <option value="DPS" {{ $build->type == 'DPS' ? 'selected' : '' }}>💥 DPS Damage Dealer</option>
                                <option value="Tanque" {{ $build->type == 'Tanque' ? 'selected' : '' }}>🛡️ Tanque Defensor</option>
                                <option value="Soporte" {{ $build->type == 'Soporte' ? 'selected' : '' }}>✨ Soporte Sanador</option>
                            </select>
                        </div>
                        <div>
                            <label>Personaje a forjar</label>
                            <select name="character_id" required>
                                <option value="">-- Selecciona el Héroe --</option>
                                @foreach($personajes as $p)
                                    <option value="{{ $p->id }}" {{ $build->character_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Estrategia Meta (Opcional)</label>
                            <select name="meta_strategy_id">
                                <option value="">-- Ninguna --</option>
                                @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->id }}" {{ $build->meta_strategy_id == $strategy->id ? 'selected' : '' }}>{{ $strategy->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SELECTOR DINÁMICO DE ARMAS -->
                <div class="form-section fade-in" style="animation-delay: 0.3s;">
                    <div class="dynamic-header">
                        <h3 class="section-title" style="margin:0;">⚔️ Armamento Principal</h3>
                        <div class="count-selector">
                            <label style="margin-bottom: 4px;">Modulos de Arma</label>
                            <select x-model.number="armasCount">
                                <option value="2">2 Armas Combinadas</option>
                                <option value="3">3 Armas Combinadas</option>
                                <option value="4">4 Armas Máximas</option>
                            </select>
                        </div>
                    </div>

                    <div class="slot-group">
                        <template x-for="i in armasCount" :key="'arma_'+i">
                            <div class="fade-in">
                                <label x-text="'Arma ' + i"></label>
                                <!-- Guardamos la selección actual en selectedArmas[i] -->
                                <select name="items[Arma][]" required x-model="selectedArmas[i]">
                                    <option value="">-- Ranura Vacía --</option>
                                    @foreach($armas as $arma)
                                        <!-- Si el id del arma está en el objeto de valores y NO es el valor del slot actual, lo desactivamos -->
                                        <option value="{{ $arma->id }}" 
                                                x-bind:disabled="Object.values(selectedArmas).includes('{{ $arma->id }}') && selectedArmas[i] !== '{{ $arma->id }}'">
                                            {{ $arma->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- SELECTOR DINÁMICO DE TOMOS -->
                <div class="form-section fade-in" style="animation-delay: 0.4s;">
                    <div class="dynamic-header">
                        <h3 class="section-title" style="margin:0;">📖 Tomos Arcanos</h3>
                        <div class="count-selector">
                            <label style="margin-bottom: 4px;">Ranuras de Tomos</label>
                            <select x-model.number="tomosCount">
                                <option value="2">2 Tomos Esenciales</option>
                                <option value="3">3 Tomos Avanzados</option>
                                <option value="4">4 Tomos Máximos</option>
                            </select>
                        </div>
                    </div>

                    <div class="slot-group">
                        <template x-for="i in tomosCount" :key="'tomo_'+i">
                            <div class="fade-in">
                                <label x-text="'Tomo ' + i"></label>
                                <select name="items[Tomo][]" required x-model="selectedTomos[i]">
                                    <option value="">-- Ranura Vacía --</option>
                                    @foreach($tomos as $tomo)
                                        <option value="{{ $tomo->id }}"
                                                x-bind:disabled="Object.values(selectedTomos).includes('{{ $tomo->id }}') && selectedTomos[i] !== '{{ $tomo->id }}'">
                                            {{ $tomo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- ACCESORIOS FIJOS -->
                <div class="form-section fade-in" style="animation-delay: 0.5s;">
                    <h3 class="section-title">🛡️ Ítems a priorizar</h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: -10px; margin-bottom: 20px;">
                        Selecciona el equipo base que consideres esencial. Las ranuras no repetidas darán ventaja.
                    </p>
                    <div class="slot-group">
                        @for($i = 1; $i <= 6; $i++)
                            <div>
                                <label>Prioridad {{ $i }}</label>
                                <select name="items[Item][]" x-model="selectedItems[{{ $i }}]">
                                    <option value="">-- Ninguno --</option>
                                    @foreach($accesorios as $item)
                                        <option value="{{ $item->id }}"
                                                x-bind:disabled="Object.values(selectedItems).includes('{{ $item->id }}') && selectedItems[{{ $i }}] !== '{{ $item->id }}'">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>

                <div style="margin-top: 40px; border:none;" class="fade-in" style="animation-delay: 0.6s;">
                    <button type="submit" class="btn-submit">Guardar Cambios</button>
                    <p style="text-align:center; font-size: 13px; color: var(--text-muted); margin-top: 20px;">
                        Cualquier actualización será visible para la comunidad.
                    </p>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
