<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}?v={{ time() }}">
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
    <main class="main-content-catalogo">

        <!-- Título y Descripción -->
        <h1 class="page-title">🎒 Catálogo de Objetos</h1>

        <p class="catalogo-intro">
            Guías sobre los items legendarios, raros y de uso único. ¡No te pierdas ninguno!
        </p>

        <!-- =======================
             FILTROS DE BÚSQUEDA
        ======================= -->
        <div class="filters-panel" style="background:#1e1e24; padding:15px; border-radius:8px; display:flex; gap:15px; margin-bottom:20px; align-items:center; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <p style="margin:0; font-weight:bold; color:#ff416c;">Filtros Instantáneos:</p>
            <div style="display:flex; gap:10px; width:100%;">
                <select id="filter-status" style="padding:10px; border-radius:6px; background:#2c2f33; color:white; border:1px solid #3f4247; font-weight:bold; cursor:pointer;">
                    <option value="all">🌟 Todos los Estados</option>
                    <option value="completed">✅ Solo Completados</option>
                    <option value="pending">⏳ Solo Pendientes</option>
                </select>
                <select id="filter-order" style="padding:10px; border-radius:6px; background:#2c2f33; color:white; border:1px solid #3f4247; font-weight:bold; cursor:pointer;">
                    <option value="asc">🔤 Orden Alfabético (A-Z)</option>
                    <option value="desc">🔤 Orden Alfabético (Z-A)</option>
                </select>
            </div>
        </div>

        <!-- =======================
             SCRIPT DE FILTROS LOCALES
        ======================= -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const filterStatus = document.getElementById('filter-status');
                const filterOrder = document.getElementById('filter-order');
                const grid = document.querySelector('.catalogo-grid');
                
                function applyFilters() {
                    const status = filterStatus.value;
                    const order = filterOrder.value;
                    let cards = Array.from(grid.querySelectorAll('.item-card'));
                    
                    cards.forEach(card => {
                        const cb = card.querySelector('input[type="checkbox"]');
                        const isChecked = cb ? cb.checked : false;
                        
                        if (status === 'completed' && !isChecked) {
                            card.style.display = 'none';
                        } else if (status === 'pending' && isChecked) {
                            card.style.display = 'none';
                        } else {
                            card.style.display = '';
                        }
                    });
                    
                    cards.sort((a, b) => {
                        const nameA = a.querySelector('.item-name').innerText.trim().toLowerCase();
                        const nameB = b.querySelector('.item-name').innerText.trim().toLowerCase();
                        return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                    });
                    
                    cards.forEach(card => grid.appendChild(card));
                }
                
                filterStatus.addEventListener('change', applyFilters);
                filterOrder.addEventListener('change', applyFilters);

                grid.addEventListener('change', (e) => {
                    if(e.target.matches('input[type="checkbox"]')) {
                        setTimeout(applyFilters, 50);
                    }
                });
            });
        </script>

        <!-- =======================
             LISTADO DE OBJETOS
        ======================= -->
        <section class="catalogo-grid">

            @foreach($items as $item)
            @php $isUnlocked = in_array($item->id, $unlockedItems ?? []); @endphp
            <div class="item-card glass-card card-item {{ $isUnlocked ? 'unlocked' : 'locked' }}">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-{{ $item->id }}" name="unl-{{ $item->id }}" data-item-id="{{ $item->id }}" {{ $isUnlocked ? 'checked' : '' }}>
                    <label for="unl-{{ $item->id }}" class="checkbox-label" title="Marcar como desbloqueado"></label>
                </div>
                
                <div class="locked-padlock">🔒</div>

                <div class="item-image-wrapper">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                    @else
                        <span class="card-icon">💎</span>
                    @endif
                </div>
                
                <div class="item-header">
                    <div class="item-info">
                        <h2 class="item-name">{{ $item->name }}</h2>
                        <div class="item-stats">
                            <span>💎 Utilidad</span>
                        </div>
                    </div>
                </div>
                
                <p class="item-desc">{!! nl2br(e($item->description)) !!}</p>
                
                @if($item->requirement)
                <div class="item-req-badge">
                    <span>🏆</span> {{ $item->requirement }}
                </div>
                @endif
            </div>
            @endforeach

        </section>

    </main>

    <!-- =======================
         FOOTER
    ======================= -->
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

    <!-- =======================
         SCRIPT DE SINCRONIZACIÓN
    ======================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.unlock-checkbox input[type="checkbox"]');
            
            checkboxes.forEach(chk => {
                chk.addEventListener('change', async function(e) {
                    const checkbox = this;
                    const isChecked = checkbox.checked;
                    const card = checkbox.closest('.item-card');
                    
                    // Deshabilitar temporalmente para evitar peticiones múltiples
                    checkbox.disabled = true;
                    
                    let itemId = checkbox.dataset.itemId || checkbox.id.replace('unl-', '');
                    
                    try {
                        const response = await fetch('{{ route('unlocks.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ item_id: itemId, is_checked: isChecked })
                        });
                        
                        // Verificar si la respuesta es JSON (si es redirección a login, no lo será)
                        const contentType = response.headers.get("content-type");
                        if (!contentType || !contentType.includes("application/json")) {
                            throw new Error('Sesión expirada o error de servidor. Por favor, recarga la página.');
                        }

                        const data = await response.json();
                        
                        if (!response.ok || data.status !== 'success') {
                            throw new Error(data.message || 'Error al sincronizar con el servidor');
                        }
                        
                        console.log('Unlock sincronizado:', data);
                        
                        // Actualizar UI visualmente
                        if (card) {
                            if (isChecked) {
                                card.classList.remove('locked');
                                card.classList.add('unlocked');
                            } else {
                                card.classList.remove('unlocked');
                                card.classList.add('locked');
                            }
                        }
                        
                        if (typeof window.showToast === 'function') {
                            window.showToast(data.message);
                        }

                    } catch (err) {
                        console.error('Error sincronizando unlock:', err);
                        alert(err.message); // Alerta al usuario del error real
                        checkbox.checked = !isChecked; // Revertir estado visual
                        
                        // Re-sincronizar clases si falló
                        if (card) {
                            if (checkbox.checked) {
                                card.classList.remove('locked');
                                card.classList.add('unlocked');
                            } else {
                                card.classList.remove('unlocked');
                                card.classList.add('locked');
                            }
                        }
                    } finally {
                        checkbox.disabled = false;
                    }
                });
            });
        });
    </script>
</body>

</html>
