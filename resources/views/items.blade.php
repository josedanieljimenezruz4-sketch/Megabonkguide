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
        <!-- =======================
             FILTROS DE BÚSQUEDA (Alpine.js Modular)
        ======================= -->
        @include('partials.unlock-filters')

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
    @include('partials.footer')

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
                        
                        // Descomentar para debug local si es necesario
                        // console.log('Unlock sincronizado:', data);
                        
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
                        // console.error('Error sincronizando unlock:', err);
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
