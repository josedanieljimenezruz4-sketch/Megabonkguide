<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Builds | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/buscador_builds.css') }}">
    <link rel="stylesheet" href="{{ asset('css/builds.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    @include('partials.header')

    <main class="main-content-builds" x-data="buildSearch()" x-init="fetchBuilds()">

        <h1 class="page-title">🔎 Buscador Avanzado de Builds</h1>

        <section class="search-layout-grid">

            <aside class="filter-panel">
                <h2>Filtros</h2>

                <form class="filter-form" @submit.prevent>

                    <div class="filter-group">
                        <label for="search-text">Buscar por título:</label>
                        <input type="text" id="search-text" placeholder="Ej: Bonk Crítico..."
                            x-model="filters.search" @input.debounce.500ms="fetchBuilds()">
                    </div>

                    <div class="filter-group">
                        <label for="character">Personaje:</label>
                        <select id="character" x-model="filters.character_id" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            @foreach($personajes as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="weapon">Arma:</label>
                        <select id="weapon" x-model="filters.weapon_id" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            @foreach($armas as $a)
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="tomo">Tomo:</label>
                        <select id="tomo" x-model="filters.tomo_id" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            @foreach($tomos as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="rating">Ranking:</label>
                        <select id="rating" x-model="filters.rating" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5 Estrellas)</option>
                            <option value="4">⭐⭐⭐⭐ (4+ Estrellas)</option>
                            <option value="3">⭐⭐⭐ (3+ Estrellas)</option>
                            <option value="2">⭐⭐ (2+ Estrellas)</option>
                            <option value="1">⭐ (1+ Estrellas)</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Tipo de Build:</label>
                        <div class="checkbox-group">
                            <label><input type="radio" value="" x-model="filters.type" @change="fetchBuilds()"> Todos</label>
                            <label><input type="radio" value="DPS" x-model="filters.type" @change="fetchBuilds()"> DPS <span class="text-sm text-gray-500" x-text="counts['DPS'] ? `(${counts['DPS']})` : '(0)'"></span></label>
                            <label><input type="radio" value="Tanque" x-model="filters.type" @change="fetchBuilds()"> Tanque <span class="text-sm text-gray-500" x-text="counts['Tanque'] ? `(${counts['Tanque']})` : '(0)'"></span></label>
                            <label><input type="radio" value="Soporte" x-model="filters.type" @change="fetchBuilds()"> Soporte <span class="text-sm text-gray-500" x-text="counts['Soporte'] ? `(${counts['Soporte']})` : '(0)'"></span></label>
                        </div>
                    </div>

                    <button type="button" @click="filters = {search: '', character_id: '', weapon_id: '', tomo_id: '', rating: '', type: ''}; fetchBuilds();" class="btn-reset">Limpiar Filtros</button>
                </form>
            </aside>

            {{-- REMOVED SECOND x-data --}}
            <section class="results-list">
                <div class="results-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 x-text="'Resultados (' + builds.length + ')'">Resultados</h2>
                    <a href="{{ route('builds.create') }}" class="btn-primary" style="padding: 10px 15px; border-radius: 8px; font-weight: bold;">+ Publicar mi Build</a>
                    <div x-show="loading" class="spinner"></div>
                </div>

                {{-- Mapear los datos que vienen del paginator de Laravel `builds.data` --}}
                <template x-for="build in builds" :key="build.id">
                    <div class="build-card fade-in" style="min-width: 0;">
                        <div class="card-header">
                            <h3 x-text="build.name"></h3>
                            <!-- Representación de Medias Estrellas con CSS -->
                            <div class="valoracion-estrellas" :style="`--rating: ${parseFloat(build.rating).toFixed(1)};`" :title="`${parseFloat(build.rating).toFixed(1)} Estrellas`"></div>
                        </div>
                        <p class="card-details">
                            <span style="font-size: 0.9em; color: #aaa;">Tipo: <span style="color: #fff;" x-text="build.type"></span></span>
                        </p>
                        <div x-data="{ expanded: false }" x-show="build.description" style="margin-top: 10px;">
                            <!-- Contenedor A (Contraído con gradiente) -->
                            <div x-show="!expanded" x-transition.opacity style="position: relative;">
                                <p class="card-description" x-text="build.description" style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; color: #ccc; line-height: 1.5; margin: 0;"></p>
                                <template x-if="build.description && build.description.length > 150">
                                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2rem; background: linear-gradient(to top, rgba(20,20,30,0.95), transparent); pointer-events: none;"></div>
                                </template>
                            </div>

                            <!-- Contenedor B (Expandido con animación x-collapse) -->
                            <div x-show="expanded" x-collapse.duration.400ms>
                                <p class="card-description" x-text="build.description" style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; color: #ccc; line-height: 1.5; margin: 0; white-space: pre-wrap;"></p>
                            </div>
                            
                            <template x-if="build.description && build.description.length > 150">
                                <button @click="expanded = !expanded" x-text="expanded ? 'Leer menos' : 'Leer más...'" style="background: none; border: none; color: #00f0ff; font-weight: bold; cursor: pointer; padding: 0; margin-top: 8px; font-size: 0.85em; transition: color 0.3s;" onmouseover="this.style.color='#00c8ff'" onmouseout="this.style.color='#00f0ff'"></button>
                            </template>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 15px;">
                            <span style="display: flex; align-items: center; gap: 6px; font-size: 0.85em; color: #aaa;">
                                Creada por 
                                <img :src="build.user ? build.user.avatar_url : '{{ asset('images/default-avatar.png') }}'" alt="Avatar" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; border: 1px solid #ffcf00;">
                                <a :href="build.user ? '/perfil/' + build.user.id : '#'" style="color: #ffcf00; font-weight: bold; text-decoration: none;" x-text="build.user ? build.user.username : 'Anónimo'"></a>
                                <template x-if="build.user && build.user.is_admin">
                                    <span style="color: #1da1f2; margin-left: -2px;" title="Verificado">☑️</span>
                                </template>
                                <span x-text="build.created_at_human ? 'hace ' + build.created_at_human : ''"></span>
                            </span>
                            <div style="display: flex; gap: 10px;">
                                <template x-if="build.user_id == {{ auth()->id() ?? 'null' }}">
                                    <a :href="'/builds/' + build.id + '/edit'" class="view-build-link" style="background: rgba(255, 255, 255, 0.1); border-color: #ffcf00; color: #ffcf00;">✏️ Editar</a>
                                </template>
                                <a :href="'/builds/' + build.id" class="view-build-link">Ver Detalles →</a>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="builds.length === 0 && !loading" class="no-results">
                    <p>No se encontraron builds con esos criterios.</p>
                </div>

            </section>

            <script>
                function buildSearch() {
                    return {
                        builds: [],
                        counts: {},
                        loading: false,
                        filters: {
                            search: '',
                            character_id: '',
                            weapon_id: '',
                            tomo_id: '',
                            rating: '',
                            type: ''
                        },
                        async fetchBuilds() {
                            this.loading = true;
                            const params = new URLSearchParams(this.filters).toString();
                            try {
                                const response = await fetch(`{{ route('builds.index') }}?${params}`, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                });
                                const data = await response.json();
                                // Paginator de laravel retorna los items en "data"
                                this.builds = data.builds.data;
                                this.counts = data.counts;
                            } catch (error) {
                                console.error('Error fetching builds:', error);
                            } finally {
                                this.loading = false;
                            }
                        }
                    }
                }
            </script>

        </section>

    </main>

    @include('partials.footer')

</body>

</html>
