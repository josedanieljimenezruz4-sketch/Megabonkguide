<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomos | UNLOCKS | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unlocks_catalogo.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-catalogo">

        <h1 class="page-title">📜 Biblioteca de Tomos</h1>

        <p class="catalogo-intro">
            Los Tomos ofrecen mejoras permanentes a tus personajes. Descubre cómo desbloquear cada volumen.
        </p>

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
                        const nameA = a.querySelector('h2').innerText.trim().toLowerCase();
                        const nameB = b.querySelector('h2').innerText.trim().toLowerCase();
                        return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                    });
                    
                    cards.forEach(card => grid.appendChild(card));
                }
                
                filterStatus.addEventListener('change', applyFilters);
                filterOrder.addEventListener('change', applyFilters);

                // Auto-refresh when completing/uncompleting an item
                grid.addEventListener('change', (e) => {
                    if(e.target.matches('input[type="checkbox"]')) {
                        setTimeout(applyFilters, 50);
                    }
                });
            });
        </script>

        <section class="catalogo-grid">

            <!-- Tomos cargados desde la Base de Datos -->

            @foreach($items as $item)
            <a href="#" class="item-card card-tomo">
                <div class="unlock-checkbox">
                    <input type="checkbox" id="unl-{{ $item->id }}" name="unl-{{ $item->id }}" data-item-id="{{ $item->id }}" {{ in_array($item->id, $unlockedItems ?? []) ? 'checked' : '' }}>
                    <label for="unl-{{ $item->id }}" class="checkbox-label"></label>
                </div>
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 60px; height: 60px; object-fit: contain; margin-bottom: 15px; border-radius: 6px; background-color: #1a1a20; padding: 4px; box-shadow: inset 0 0 5px rgba(0,0,0,0.5);">
                @else
                    <span class="card-icon">📖</span>
                @endif
                <h2>{{ $item->name }}</h2>
                <p>{!! nl2br(e($item->description)) !!}</p>
                @if($item->requirement)
                <p class="unlock-req">{{ $item->requirement }}</p>
                @endif
            </a>
            @endforeach

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
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.unlock-checkbox input[type="checkbox"]');
            
            checkboxes.forEach(chk => {
                chk.addEventListener('change', async function(e) {
                    e.stopImmediatePropagation();
                    
                    const checkbox = this;
                    const isChecked = checkbox.checked;
                    
                    let itemId = checkbox.dataset.itemId || checkbox.value;
                    if (!itemId || itemId === 'on') {
                        itemId = checkbox.id.replace('unl-', '');
                    }
                    
                    try {
                        const response = await fetch('{{ route('unlocks.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ item_id: itemId, is_checked: isChecked })
                        });
                        
                        const data = await response.json();
                        
                        if (!response.ok || data.status !== 'success') {
                            throw new Error('Respuesta fallida del servidor');
                        }
                        
                        console.log('Unlock sincronizado:', data);
                        if (typeof window.showToast === 'function') {
                            window.showToast(isChecked ? '✨ Ítem desbloqueado' : '❌ Ítem bloqueado');
                        }
                    } catch (err) {
                        console.error('Error sincronizando unlock:', err);
                        checkbox.checked = !isChecked; // Revertir en caso de error
                    }
                });
            });
        });
    </script>
</body>

</html>