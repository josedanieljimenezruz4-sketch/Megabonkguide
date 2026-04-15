<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $build->name }} | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="icon" href="{{ asset('iconotlabaho.webp') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-glow: #B965F0;
            --secondary-glow: #ff416c;
            --bg-dark: #0f1016;
            --glass-bg: rgba(255, 255, 255, 0.04);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #fcfcfc;
            --text-muted: #a0a0b0;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(185, 101, 240, 0.1), transparent 40%),
                radial-gradient(circle at 100% 50%, rgba(255, 65, 108, 0.05), transparent 40%);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .fade-in { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .build-detail-container {
            max-width: 900px;
            margin: 100px auto 50px;
            padding: 40px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .build-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            background: linear-gradient(135deg, #fff, var(--primary-glow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .build-meta {
            font-size: 1rem;
            color: var(--text-muted);
            display: flex;
            gap: 15px;
        }

        .badge {
            background: rgba(185, 101, 240, 0.2);
            color: #d8b4fe;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(185, 101, 240, 0.3);
        }

        .interactive-rating-widget {
            text-align: right;
            background: rgba(0,0,0,0.3);
            padding: 15px 25px;
            border-radius: 15px;
            border: 1px solid var(--glass-border);
        }

        .interactive-stars {
            font-size: 2rem;
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: #333; /* Estrellas vacías */
            letter-spacing: 2px;
            user-select: none;
        }

        .interactive-stars .stars-fill {
            position: absolute;
            top: 0; left: 0;
            color: #FFD700;
            overflow: hidden;
            white-space: nowrap;
            pointer-events: none;
            transition: width 0.1s ease;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
        }

        .grid-section {
            margin-bottom: 40px;
        }

        .grid-section h3 {
            font-size: 1.5rem;
            border-left: 4px solid var(--secondary-glow);
            padding-left: 10px;
            margin-bottom: 20px;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .item-card {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(185, 101, 240, 0.2);
            border-color: rgba(185, 101, 240, 0.4);
        }

        .item-card h4 {
            margin: 0;
            color: #fff;
            font-size: 1.1rem;
        }

        .description-box {
            background: rgba(255, 255, 255, 0.02);
            padding: 20px;
            border-radius: 12px;
            color: #ddd;
            line-height: 1.6;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    @include('partials.header')

    <main class="fade-in" style="animation-delay: 0.1s;">
        <div class="build-detail-container">
            
            <div class="header-section">
                <div>
                    <h1 class="build-title">{{ $build->name }}</h1>
                    <div class="build-meta">
                        <span class="badge">{{ $build->type ?? 'Híbrido' }}</span>
                        <span>Forjado por: ID {{ $build->user_id }}</span> <!-- Reemplazar con Auth()->user->name cuando lo tengas -->
                        <span>Autorizado: {{ $build->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- WIDGET DE VOTACIÓN ALPINE -->
                <div class="interactive-rating-widget" 
                     x-data="{
                        rating: {{ $build->rating }},
                        hoverRating: {{ $userVote ? $userVote : $build->rating }},
                        userVote: {{ $userVote ? $userVote : 'null' }},
                        isSubmitting: false,
                        message: '',
                        
                        calculateRating(e) {
                            const rect = e.currentTarget.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const percentage = x / rect.width;
                            let score = percentage * 5;
                            this.hoverRating = Math.max(1, Math.ceil(score * 2) / 2);
                        },

                        async submitVote() {
                            @guest
                                alert('Debes iniciar sesión para votar.');
                                return;
                            @endguest

                            this.isSubmitting = true;
                            try {
                                const response = await fetch('{{ route('builds.vote', $build->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ score: this.hoverRating })
                                });
                                
                                const data = await response.json();
                                if(data.success) {
                                    this.rating = data.new_rating;
                                    this.userVote = this.hoverRating;
                                    this.message = data.message;
                                    setTimeout(() => this.message = '', 3000);
                                }
                            } catch(err) {
                                console.error(err);
                            } finally {
                                this.isSubmitting = false;
                            }
                        }
                     }">
                    <div style="font-size: 0.85rem; color: #aaa; margin-bottom: 5px;" x-text="userVote ? 'Tu Voto:' : 'Califica esta Build'"></div>
                    
                    <div class="interactive-stars" 
                         @mousemove="calculateRating($event)" 
                         @mouseleave="hoverRating = userVote || rating" 
                         @click="submitVote"
                         :style="isSubmitting ? 'opacity: 0.5; pointer-events:none;' : ''">
                        ★★★★★
                        <div class="stars-fill" :style="`width: ${(hoverRating / 5) * 100}%;`">★★★★★</div>
                    </div>
                    
                    <div style="font-size: 1.2rem; font-weight: bold; margin-top: 5px;">
                        Global: <span x-text="parseFloat(rating).toFixed(1)"></span> <span style="font-size:0.9rem; color:#888;">/ 5.0</span>
                    </div>

                    <div x-show="message" x-text="message" style="color: #4ade80; font-size: 0.8rem; margin-top: 5px;" class="fade-in"></div>
                </div>
            </div>

            @php
            // Helper para las imágenes como se usa en la Meta
            if (!function_exists('renderItemImage')) {
                function renderItemImage($item) {
                    if(!$item) return asset('images/placeholder.png');
                    if(!$item->image_path) return asset('images/placeholder.png');
                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                        return asset('storage/' . $item->image_path);
                    }
                    return asset('images/' . $item->image_path);
                }
            }
            @endphp

            <!-- DESCRIPCIÓN -->
            @if($build->description)
            <div class="grid-section fade-in" style="animation-delay: 0.2s;">
                <h3>📝 Estrategia de la Build</h3>
                <div class="description-box">
                    {{ $build->description }}
                </div>
            </div>
            @endif

            <!-- PERSONAJE -->
            <div class="grid-section fade-in" style="animation-delay: 0.3s;">
                <h3>🎭 Personaje Principal</h3>
                <div class="item-card" style="display:inline-block; padding: 20px 40px; text-align: center;">
                    <img src="{{ renderItemImage($build->character) }}" alt="Personaje" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 15px; border-radius: 8px; filter: drop-shadow(0 0 10px rgba(185,101,240,0.5));">
                    <h4 style="font-size: 1.3rem;">{{ $build->character ? $build->character->name : 'Personaje Misterioso' }}</h4>
                </div>
            </div>

            <!-- ARMAS -->
            <div class="grid-section fade-in" style="animation-delay: 0.4s;">
                <h3>⚔️ Armamento Principal</h3>
                <div class="items-grid">
                    @foreach($build->items->where('pivot.slot_type', 'Arma') as $arma)
                        <div class="item-card">
                            <img src="{{ renderItemImage($arma) }}" alt="{{ $arma->name }}" style="width: 50px; height: 50px; object-fit: contain; margin-bottom: 10px; border-radius: 5px; background: rgba(0,0,0,0.4); padding: 5px;">
                            <h4>{{ $arma->name }}</h4>
                        </div>
                    @endforeach
                    @if($build->items->where('pivot.slot_type', 'Arma')->isEmpty())
                        <p style="color:#777;">Sin armas asignadas.</p>
                    @endif
                </div>
            </div>

            <!-- TOMOS -->
            <div class="grid-section fade-in" style="animation-delay: 0.5s;">
                <h3>📖 Conocimiento Arcano (Tomos)</h3>
                <div class="items-grid">
                    @foreach($build->items->where('pivot.slot_type', 'Tomo') as $tomo)
                        <div class="item-card">
                            <img src="{{ renderItemImage($tomo) }}" alt="{{ $tomo->name }}" style="width: 50px; height: 50px; object-fit: contain; margin-bottom: 10px; border-radius: 5px; background: rgba(0,0,0,0.4); padding: 5px;">
                            <h4>{{ $tomo->name }}</h4>
                        </div>
                    @endforeach
                    @if($build->items->where('pivot.slot_type', 'Tomo')->isEmpty())
                        <p style="color:#777;">Sin tomos asignados.</p>
                    @endif
                </div>
            </div>

            <!-- ACCESORIOS -->
            <div class="grid-section fade-in" style="animation-delay: 0.6s;">
                <h3>🛡️ Ítems a Priorizar</h3>
                <div class="items-grid">
                    @foreach($build->items->where('pivot.slot_type', 'Item') as $item)
                        <div class="item-card">
                            <img src="{{ renderItemImage($item) }}" alt="{{ $item->name }}" style="width: 50px; height: 50px; object-fit: contain; margin-bottom: 10px; border-radius: 5px; background: rgba(0,0,0,0.4); padding: 5px;">
                            <h4>{{ $item->name }}</h4>
                        </div>
                    @endforeach
                    @if($build->items->where('pivot.slot_type', 'Item')->isEmpty())
                        <p style="color:#777;">Sin ítems a priorizar.</p>
                    @endif
                </div>
            </div>

        </div>
    </main>

</body>
</html>
