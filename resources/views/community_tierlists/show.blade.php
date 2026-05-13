<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tierList->titulo }} | Tier List de la Comunidad</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-tierlist">

        <h1 class="page-title">{{ $tierList->titulo }}</h1>
        
        <div class="intro-text-tierlist" style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #ffcf00; margin-bottom: 20px;">
            <span>Creada por:</span>
            <x-user-avatar :user="$tierList->user" size="30" style="border: 2px solid #ffcf00;" />
            <a href="{{ $tierList->user ? url('/perfil/' . $tierList->user->id) : '#' }}" style="color: #ffcf00; font-weight: bold; text-decoration: none;">
                {{ $tierList->user->username ?? 'Usuario Anónimo' }}
            </a>
            @if($tierList->user && $tierList->user->is_admin)
                <span style="color: #1da1f2; margin-left: 2px;" data-tippy-content="Tier List Oficial de Megabonk Guide">☑️</span>
            @endif
            <span style="color: #aaa; margin-left: 10px;">| Categoría: {{ ucfirst($tierList->categoria) }}</span>
            @if(auth()->check() && auth()->id() == $tierList->user_id)
                <a href="{{ route('community-tierlists.edit', $tierList->id) }}" style="margin-left: 10px; background: rgba(255, 255, 255, 0.1); color: #ffcf00; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.9em; border: 1px solid #ffcf00;">✏️ Editar Tier List</a>
            @endif
        </div>

        @if($tierList->descripcion)
        <div style="background: #2c2f33; padding: 20px; border-radius: 10px; margin-bottom: 30px; margin-inline: auto; max-width: 800px;">
            <p style="margin: 0; color: #ddd; text-align: center; font-style: italic;">
                "{{ $tierList->descripcion }}"
            </p>
        </div>
        @endif

        <div class="tierlist-container">
            @php
                $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
            @endphp

            @foreach($ranksOrder as $rank)
                <div class="tier-row tier-{{ strtolower($rank) }}">
                    <div class="tier-rank">{{ $rank }}</div>
                    <div class="tier-items">
                        @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                            @foreach($itemsByRank[$rank] as $item)
                                <div class="tier-item" data-tippy-content="{{ $item->description ?? 'Sin descripción.' }}">
                                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}"
                                        title="{{ $item->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                    <span>{{ $item->name }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="comments-section" style="margin-top: 50px; background: #1a1a1a; padding: 20px; border-radius: 10px; max-width: 800px; margin-inline: auto; border: 1px solid #333; text-align: left;">
            <h2 style="border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 0;">💬 Comentarios</h2>

            <div class="comments-list" style="margin-top: 20px;">
                @forelse($tierList->comments as $comment)
                    @include('community.partials.comment', ['comment' => $comment, 'depth' => 0, 'submitUrl' => route('community-tierlists.comment', $tierList->id)])
                @empty
                    <p style="color: #aaa; text-align: center; font-style: italic;">No hay comentarios aún. ¡Sé el primero en comentar!</p>
                @endforelse
            </div>

            <div class="comment-form" style="margin-top: 30px; border-top: 1px solid #333; padding-top: 20px;">
                @auth
                    <form action="{{ route('community-tierlists.comment', $tierList->id) }}" method="POST" class="ajax-comment-form">
                        @csrf
                        <input type="hidden" name="depth" value="0">
                        <div style="margin-bottom: 15px;">
                            <textarea name="content" rows="4" placeholder="Escribe tu comentario aquí..." required style="width: 100%; background: #222; border: 1px solid #444; color: white; padding: 10px; border-radius: 8px; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-link" style="border: none; cursor: pointer; padding: 10px 20px; font-size: 1em;">Enviar Comentario</button>
                    </form>
                @else
                    <div style="text-align: center; padding: 15px; background: #222; border-radius: 8px; border: 1px dashed #444;">
                        <p style="margin: 0; color: #aaa;">Debes <a href="{{ route('login') }}" style="color: #ffcf00; text-decoration: none;">iniciar sesión</a> para comentar.</p>
                    </div>
                @endauth
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('tierlist') }}" class="btn btn-secondary-link">Volver a todas las Tier Lists</a>
        </div>

    </main>

    @include('partials.footer')

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[data-tippy-content]', {
                theme: 'tierlist',
                placement: 'top',
                arrow: true
            });
        });

        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function toggleReplies(commentId) {
            const repliesDiv = document.getElementById('replies-' + commentId);
            const btn = document.getElementById('toggle-replies-btn-' + commentId);
            const arrow = btn.querySelector('.arrow-icon');
            const btnText = btn.querySelector('.btn-text');
            
            if (repliesDiv.style.display === 'none' || repliesDiv.style.display === '') {
                repliesDiv.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
                btnText.innerHTML = btnText.innerHTML.replace('Ver', 'Ocultar');
            } else {
                repliesDiv.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
                btnText.innerHTML = btnText.innerHTML.replace('Ocultar', 'Ver');
            }
        }

        document.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('ajax-comment-form')) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('.btn-primary-link, .btn-submit');
                const originalText = submitBtn.innerText;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Enviando...';
                
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalText;
                    
                    if (data.success) {
                        form.querySelector('textarea').value = ''; // clear form
                        
                        // Close reply form if it's a nested form
                        const cancelBtn = form.querySelector('.btn-cancel');
                        if (cancelBtn) cancelBtn.click();
                        
                        if (data.parent_id) {
                            // Append to replies container
                            const repliesContainer = document.getElementById('replies-' + data.parent_id);
                            if (repliesContainer) {
                                repliesContainer.insertAdjacentHTML('beforeend', data.html);
                                // Ensure container is visible
                                repliesContainer.style.display = 'block';
                                
                                // Update count button
                                const toggleBtn = document.getElementById('toggle-replies-btn-' + data.parent_id);
                                if (toggleBtn) {
                                    toggleBtn.style.display = 'flex'; // make sure it's visible
                                    const arrow = toggleBtn.querySelector('.arrow-icon');
                                    arrow.style.transform = 'rotate(180deg)'; // point up
                                    const btnText = toggleBtn.querySelector('.btn-text');
                                    // Update number
                                    const countSpans = document.querySelectorAll('.replies-count-' + data.parent_id);
                                    countSpans.forEach(span => {
                                        span.innerText = parseInt(span.innerText || 0) + 1;
                                    });
                                    if (btnText.innerHTML.includes('Ver')) {
                                        btnText.innerHTML = btnText.innerHTML.replace('Ver', 'Ocultar');
                                    }
                                }
                            }
                        } else {
                            // Append to root list
                            const rootContainer = document.querySelector('.comments-list');
                            if (rootContainer) {
                                rootContainer.insertAdjacentHTML('beforeend', data.html);
                            }
                            
                            // Remove "No hay comentarios" message if exists
                            const noMsg = rootContainer.querySelector('p[style*="font-style: italic"]');
                            if (noMsg) noMsg.remove();
                        }
                    } else {
                        alert('Error al enviar el comentario.');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalText;
                    alert('Hubo un error en la conexión.');
                });
            }
        });
    </script>
</body>
</html>
