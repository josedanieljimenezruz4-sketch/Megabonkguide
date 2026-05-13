<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info General | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/info_general.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>

<body>

    @include('partials.header')

    <main class="main-content-info">

        <h1 class="page-title">📚 Información General y Ayuda (Wiki)</h1>

        <form action="{{ route('wiki.index') }}" method="GET" class="wiki-search-form" onsubmit="return false;">
            <input type="text" name="search" id="wikiSearchInput" placeholder="Busca al instante (info o preguntas)..." value="{{ request('search') }}" class="wiki-search-input">
            <!-- Botones ocultos ya que la búsqueda es en tiempo real -->
            <button type="submit" class="wiki-search-btn" style="display: none;">Buscar</button>
            <button type="button" class="wiki-clear-btn" id="wikiClearBtn" style="display: none;">Limpiar</button>
        </form>

        <div class="wiki-container">

            <!-- ================= SOBRE EL JUEGO ================= -->
            <section class="wiki-section info-section" style="margin-bottom: 40px;">
                <h2 style="color: var(--neon-cyan, #41E8EF); border-bottom: 2px solid var(--neon-cyan, #41E8EF); padding-bottom: 10px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <span>🎮 Sobre el Juego / Mecánicas</span>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('admin.wiki.index') }}" style="font-size: 0.5em; background: #333; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;">✏️ Editar (Admin)</a>
                    @endif
                </h2>
                
                @if($infos->isEmpty())
                    <p class="section-intro">No se encontró información.</p>
                @else
                    @foreach($infos as $info)
                        <div class="wiki-entry" style="border-left-color: var(--neon-cyan, #41E8EF);">
                            <h3 style="margin-top: 0; color: var(--text-light); display: flex; align-items: center; gap: 10px;">
                                📖 {{ $info->title }}
                            </h3>
                            @if($info->category)
                                <span class="wiki-category badge" style="background-color: #333; border: 1px solid var(--neon-cyan, #41E8EF);">{{ $info->category }}</span>
                            @endif
                            <div class="wiki-content">
                                {!! Str::markdown($info->content) !!}
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>

            <!-- ================= PREGUNTAS FRECUENTES ================= -->
            <section class="wiki-section faq-section">
                <h2 style="color: var(--neon-purple, #B965F0); border-bottom: 2px solid var(--neon-purple, #B965F0); padding-bottom: 10px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <span>❓ Preguntas Frecuentes (FAQ)</span>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('admin.wiki.index') }}" style="font-size: 0.5em; background: #333; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;">✏️ Editar (Admin)</a>
                    @endif
                </h2>
                
                @if($faqs->isEmpty())
                    <p class="section-intro">No se encontraron preguntas frecuentes.</p>
                @else
                    @foreach($faqs as $faq)
                        <details class="faq-item" style="border-left-color: var(--neon-purple, #B965F0);">
                            <summary style="display: flex; align-items: center;">
                                ❓ {{ $faq->title }}
                                @if($faq->category)
                                    <span class="wiki-category badge" style="font-size: 0.8em; margin-left: auto; background-color: #333; border: 1px solid var(--neon-purple, #B965F0);">{{ $faq->category }}</span>
                                @endif
                            </summary>
                            <div class="faq-content" style="padding: 10px 15px; color: var(--text-muted);">
                                {!! Str::markdown($faq->content) !!}
                            </div>
                        </details>
                    @endforeach
                @endif
            </section>

        </div>
    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('wikiSearchInput');
            const clearBtn = document.getElementById('wikiClearBtn');
            const infoEntries = document.querySelectorAll('.wiki-entry');
            const faqEntries = document.querySelectorAll('.faq-item');

            function filterEntries() {
                const query = searchInput.value.toLowerCase().trim();

                // Show/hide clear button
                if (query.length > 0) {
                    clearBtn.style.display = 'block';
                } else {
                    clearBtn.style.display = 'none';
                }

                // Filter Info Entries
                infoEntries.forEach(entry => {
                    const text = entry.textContent.toLowerCase();
                    if (text.includes(query)) {
                        entry.style.display = '';
                        // Forzar un reflow para que la transición funcione
                        void entry.offsetWidth;
                        entry.classList.remove('wiki-hidden-anim');
                    } else {
                        entry.classList.add('wiki-hidden-anim');
                        setTimeout(() => {
                            // Solo ocultamos si sigue teniendo la clase (por si el usuario borró rápido)
                            if (entry.classList.contains('wiki-hidden-anim')) {
                                entry.style.display = 'none';
                            }
                        }, 300);
                    }
                });

                // Filter FAQ Entries
                faqEntries.forEach(entry => {
                    const text = entry.textContent.toLowerCase();
                    if (text.includes(query)) {
                        entry.style.display = '';
                        void entry.offsetWidth;
                        entry.classList.remove('wiki-hidden-anim');
                    } else {
                        entry.classList.add('wiki-hidden-anim');
                        setTimeout(() => {
                            if (entry.classList.contains('wiki-hidden-anim')) {
                                entry.style.display = 'none';
                            }
                        }, 300);
                    }
                });
            }

            // Listen for input events (typing)
            searchInput.addEventListener('input', filterEntries);

            // Handle clear button
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterEntries();
                searchInput.focus();
            });

            // Initial filter if there's a value loaded from server
            if (searchInput.value) {
                filterEntries();
            }

            // Accordion Animation for FAQs
            faqEntries.forEach(detail => {
                const summary = detail.querySelector('summary');
                const content = detail.querySelector('.faq-content');
                
                summary.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default instant toggle
                    
                    if (detail.hasAttribute('open')) {
                        // Close animation
                        content.style.height = content.offsetHeight + 'px';
                        void content.offsetHeight; // force reflow
                        content.style.height = '0px';
                        content.style.opacity = '0';
                        content.style.paddingTop = '0';
                        content.style.paddingBottom = '0';
                        
                        setTimeout(() => {
                            detail.removeAttribute('open');
                            content.style.height = '';
                            content.style.opacity = '';
                            content.style.paddingTop = '';
                            content.style.paddingBottom = '';
                        }, 300); // match transition time
                    } else {
                        // Open animation
                        detail.setAttribute('open', '');
                        const height = content.offsetHeight; // get natural height
                        
                        // Reset for animation
                        content.style.height = '0px';
                        content.style.opacity = '0';
                        // Keep padding as is in CSS, just animate height/opacity
                        
                        void content.offsetHeight; // force reflow
                        
                        content.style.height = height + 'px';
                        content.style.opacity = '1';
                        
                        setTimeout(() => {
                            content.style.height = 'auto'; // allow text to resize if window resizes
                        }, 300);
                    }
                });
            });

            // Read More functionality for long Info content
            const wikiContents = document.querySelectorAll('.wiki-content');
            wikiContents.forEach(content => {
                // Si el contenido es más alto que 140px, lo truncamos
                if (content.scrollHeight > 140) {
                    content.classList.add('wiki-content-clamped');
                    
                    const btn = document.createElement('button');
                    btn.className = 'read-more-btn';
                    btn.innerHTML = 'Leer más...';
                    
                    // Insertar botón después del contenido
                    content.parentNode.insertBefore(btn, content.nextSibling);
                    
                    btn.addEventListener('click', () => {
                        if (content.classList.contains('wiki-content-clamped')) {
                            content.classList.remove('wiki-content-clamped');
                            content.classList.add('wiki-content-expanded');
                            btn.innerHTML = 'Mostrar menos';
                        } else {
                            content.classList.remove('wiki-content-expanded');
                            content.classList.add('wiki-content-clamped');
                            btn.innerHTML = 'Leer más...';
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
