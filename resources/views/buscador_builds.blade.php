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
</head>

<body>

    @include('partials.header')

    <main class="main-content-builds" x-data="buildSearch()" x-init="fetchBuilds()">

        <h1 class="page-title">🔎 Buscador Avanzado de Builds</h1>

        <section class="search-layout-grid">

            <aside class="filter-panel">
                <h2>Filtros</h2>

                <form class="filter-form">

                    <div class="filter-group">
                        <label for="search-text">Buscar por título:</label>
                        <input type="text" id="search-text" placeholder="Ej: Bonk Crítico, Velocidad Extrema"
                            x-model="filters.search" @input.debounce.500ms="fetchBuilds()">
                    </div>

                    <div class="filter-group">
                        <label for="character">Personaje:</label>
                        <select id="character" x-model="filters.character" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            <option value="Maestra del Bonk">La Maestra del Bonk</option>
                            <option value="Berserker">El Berserker</option>
                            <option value="Ilusionista">La Ilusionista</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="weapon">Arma principal:</label>
                        <select id="weapon" x-model="filters.weapon" @change="fetchBuilds()">
                            <option value="">Cualquiera</option>
                            <option value="Hacha Púrpura">Hacha Púrpura Radiante</option>
                            <option value="Bastón de Cobre">Bastón de Cobre</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="rating">Rating Mínimo:</label>
                        <input type="range" id="rating" min="1" max="5" value="3">
                        <span id="rating-value">3 Estrellas</span>
                    </div>

                    <div class="filter-group">
                        <label>Tipo de Build:</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" checked> DPS</label>
                            <label><input type="checkbox"> Tanque</label>
                            <label><input type="checkbox"> Soporte</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-search">Aplicar Filtros</button>
                    <button type="reset" class="btn-reset">Limpiar Filtros</button>
                </form>
            </aside>

            <section class="results-list" x-data="buildSearch()" x-init="fetchBuilds()">
                <div class="results-header">
                    <h2 x-text="'Resultados (' + builds.length + ')'">Resultados</h2>
                    <div x-show="loading" class="spinner"></div>
                </div>

                <template x-for="build in builds" :key="build.title">
                    <div class="build-card fade-in">
                        <div class="card-header">
                            <h3 x-text="build.title"></h3>
                            <span class="rating" x-text="'⭐'.repeat(build.rating)"></span>
                        </div>
                        <p class="card-details">
                            **Personaje:** <span x-text="build.character"></span> |
                            **Arma:** <span x-text="build.weapon"></span> |
                            **Autor:** <span x-text="build.author"></span>
                        </p>
                        <p class="card-description" x-text="build.description"></p>
                        <a href="#" class="view-build-link">Ver Build Completa →</a>
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
                        loading: false,
                        filters: {
                            search: '',
                            character: '',
                            weapon: ''
                        },
                        init() {
                            // Watch for changes in inputs handled outside x-data scope if needed, 
                            // or better, move inputs inside or use event listeners.
                            // Since inputs are in a sibling <aside>, we can use window events or 
                            // put x-data on a higher parent.
                            // Let's put x-data on the <main;> or simply listen to events.
                        },
                        async fetchBuilds() {
                            this.loading = true;
                            const params = new URLSearchParams(this.filters).toString();
                            try {
                                const response = await fetch(`{{ route('builds.search') }}?${params}`, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                this.builds = await response.json();
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