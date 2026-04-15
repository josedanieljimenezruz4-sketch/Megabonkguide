<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Builds | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buscador_builds.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .star-rating-display {
            display: inline-block;
            font-size: 1.2rem;
            position: relative;
            color: #444; /* Estrellas vacías oscuras */
            letter-spacing: 2px;
        }
        .star-rating-display::before {
            content: "★★★★★";
        }
        .star-rating-display::after {
            content: "★★★★★";
            color: #FFD700; /* Oro vibrante */
            position: absolute;
            left: 0;
            top: 0;
            white-space: nowrap;
            overflow: hidden;
            width: calc(var(--rating) * 20%);
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.4);
        }
    </style>
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
                            <option value="hacha-purpura">La Maestra del Bonk</option>
                            {{-- Tienes que pasarlos desde el backend o usar texto fijo por ahora --}}
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

                    <button type="button" @click="filters = {search: '', character_id: '', type: ''}; fetchBuilds();" class="btn-reset">Limpiar Filtros</button>
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
                    <div class="build-card fade-in">
                        <div class="card-header">
                            <h3 x-text="build.name"></h3>
                            <!-- Representación de Medias Estrellas con CSS -->
                            <div class="star-rating-display" :style="`--rating: ${parseFloat(build.rating).toFixed(1)};`" :title="`${parseFloat(build.rating).toFixed(1)} Estrellas`"></div>
                        </div>
                        <p class="card-details">
                            **Tipo:** <span x-text="build.type"></span> |
                            **Autor ID:** <span x-text="build.user_id"></span>
                        </p>
                        <p class="card-description" x-text="build.description"></p>
                        <a :href="'/builds/' + build.id" class="view-build-link">Ver Detalles →</a>
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

</body>

</html>