<!-- Alpine.js (Requerido para los filtros dinámicos) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- =======================
     FILTROS DE BÚSQUEDA (Alpine.js Modular)
======================= -->
<div class="filters-panel" x-data="unlocksFilters()" style="background:#1e1e24; padding:15px; border-radius:8px; display:flex; gap:15px; margin-bottom:20px; align-items:center; flex-wrap:wrap; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
    <p style="margin:0; font-weight:bold; color:#ff416c;">Filtros Instantáneos:</p>
    
    <div style="display:flex; gap:10px; flex-wrap:wrap; width:100%; align-items:center;">
        
        <!-- NUEVO: Buscador por Nombre -->
        <input type="text" 
               x-model.debounce.300ms="search" 
               placeholder="Buscar por nombre..." 
               style="padding:10px; border-radius:6px; background:#111; color:white; border:2px solid #00f3ff; font-weight:bold; flex-grow: 1; outline: none; transition: box-shadow 0.3s;"
               onfocus="this.style.boxShadow='0 0 10px rgba(0, 243, 255, 0.5)'"
               onblur="this.style.boxShadow='none'">

        <!-- Selectores Existentes -->
        <select x-model="filter" style="padding:10px; border-radius:6px; background:#2c2f33; color:white; border:1px solid #3f4247; font-weight:bold; cursor:pointer;">
            <option value="all">🌟 Todos los Estados</option>
            <option value="completed">✅ Solo Completados</option>
            <option value="pending">⏳ Solo Pendientes</option>
        </select>
        
        <select x-model="order" style="padding:10px; border-radius:6px; background:#2c2f33; color:white; border:1px solid #3f4247; font-weight:bold; cursor:pointer;">
            <option value="asc">🔤 Orden Alfabético (A-Z)</option>
            <option value="desc">🔤 Orden Alfabético (Z-A)</option>
        </select>
    </div>
</div>

<style>
/* Animación de entrada para los ítems */
@keyframes fadeInSlideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.fade-in-item {
    animation: fadeInSlideUp 0.4s ease-out forwards;
    opacity: 0; /* Empieza invisible hasta que la animación actúe */
}
</style>

<script>
function unlocksFilters() {
    return {
        search: '{{ request('search', '') }}',
        filter: '{{ request('filter', 'all') }}',
        order: '{{ request('order', 'asc') }}',
        
        init() {
            // $watch escucha los cambios reales en las variables respetando el debounce
            this.$watch('search', () => this.fetchData());
            this.$watch('filter', () => this.fetchData());
            this.$watch('order', () => this.fetchData());
        },
        
        fetchData() {
            const params = new URLSearchParams({
                search: this.search,
                filter: this.filter,
                order: this.order
            });
            
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState(null, '', newUrl);

            fetch(newUrl)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newGrid = doc.querySelector('.catalogo-grid');
                    
                    if (newGrid) {
                        document.querySelector('.catalogo-grid').innerHTML = newGrid.innerHTML;
                        
                        // Aplicar animación Fade-in a las nuevas tarjetas
                        const cards = document.querySelectorAll('.catalogo-grid .item-card');
                        cards.forEach((card, index) => {
                            card.classList.add('fade-in-item');
                            // Efecto cascada (opcional, los muestra uno tras otro rápidamente)
                            card.style.animationDelay = `${index * 0.03}s`; 
                        });
                    }
                    
                    // Lógica para el estado vacío
                    const existingEmptyState = document.querySelector('.empty-state-msg');
                    if (existingEmptyState) existingEmptyState.remove();

                    if (newGrid && newGrid.children.length === 0) {
                        const emptyHtml = `<div class="empty-state-msg" style="text-align: center; padding: 40px; color: #00f3ff; text-shadow: 0 0 10px rgba(0,243,255,0.5); font-size: 1.2rem; font-weight: bold; border: 1px dashed #00f3ff; border-radius: 8px; margin-top: 20px;">No se encontraron elementos con ese nombre o combinación de filtros. 🚫</div>`;
                        document.querySelector('.catalogo-grid').insertAdjacentHTML('beforebegin', emptyHtml);
                    }
                })
                .catch(error => console.error("Error al cargar los datos:", error));
        }
    }
}
</script>