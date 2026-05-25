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

            <section class="results-list">
                <div class="results-header">
                    <h2 x-text="'Resultados (' + builds.length + ')'">Resultados</h2>
                    <a href="{{ route('builds.create') }}" class="btn-primary btn-publish-build">+ Publicar mi Build</a>
                    <div x-show="loading" class="spinner"></div>
                </div>

                {{-- Mapear los datos que vienen del paginator de Laravel `builds.data` --}}
                <template x-for="build in builds" :key="build.id">
                    <div class="build-card fade-in">
                        <div class="card-header">
                            <h3 x-text="build.name"></h3>
                            <!-- Representación de Medias Estrellas con CSS -->
                            <div class="valoracion-estrellas" :style="`--rating: ${parseFloat(build.rating).toFixed(1)};`" :title="`${parseFloat(build.rating).toFixed(1)} Estrellas`"></div>
                        </div>
                        <p class="card-details">
                            <span class="card-detail-label">Tipo: <span class="card-detail-value" x-text="build.type"></span></span>
                        </p>
                        <div x-data="{ expanded: false }" x-show="build.description" class="build-description-toggle">
                            <!-- Contenedor A (Contraído con gradiente) -->
                            <div x-show="!expanded" x-transition.opacity style="position: relative;">
                                <p class="card-description build-description-collapsed" x-text="build.description"></p>
                                <template x-if="build.description && build.description.length > 150">
                                    <div class="build-description-fade"></div>
                                </template>
                            </div>

                            <!-- Contenedor B (Expandido con animación x-collapse) -->
                            <div x-show="expanded" x-collapse.duration.400ms>
                                <p class="card-description build-description-expanded" x-text="build.description"></p>
                            </div>
                            
                            <template x-if="build.description && build.description.length > 150">
                                <button @click="expanded = !expanded" x-text="expanded ? 'Leer menos' : 'Leer más...'" class="btn-read-more"></button>
                            </template>
                        </div>
                        
                        <div class="build-card-footer">
                            <span class="build-author-info">
                                Creada por 
                                <img :src="build.user ? build.user.avatar_url : '{{ asset('images/default-avatar.png') }}'" alt="Avatar" class="build-author-avatar">
                                <a :href="build.user ? '/perfil/' + build.user.id : '#'" class="build-author-link" x-text="build.user ? build.user.username : 'Anónimo'"></a>
                                <template x-if="build.user && build.user.is_admin">
                                    <span class="build-verified-icon" title="Verificado">☑️</span>
                                </template>
                                <span x-text="build.created_at_human ? 'hace ' + build.created_at_human : ''"></span>
                            </span>
                            <div class="build-card-actions">
                                <template x-if="build.user_id == {{ auth()->id() ?? 'null' }}">
                                    <a :href="'/builds/' + build.id + '/edit'" class="view-build-link btn-edit-build">✏️ Editar</a>
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
                        // Obtiene builds del backend según los filtros activos
                        async fetchBuilds() {
                            this.loading = true;
                            const params = new URLSearchParams(this.filters).toString();
                            try {
                                const response = await fetch(`{{ route('builds.index') }}?${params}`, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                });
                                const data = await response.json();
                                // Paginator de Laravel retorna los items en "data"
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
