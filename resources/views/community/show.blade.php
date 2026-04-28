<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Comunidad</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comunity.css') }}">
    <link rel="icon" href="/iconotlabaho.webp" type="image/x-icon">
    <style>
        .post-detail { max-width: 800px; margin: 40px auto; background: #1e1e2e; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .post-detail-header { border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .post-detail-header h1 { margin-top: 10px; font-size: 2em; color: #fff; }
        .post-detail-content { font-size: 1.1em; line-height: 1.6; color: #ccc; white-space: pre-line; }
        .comments-section { max-width: 800px; margin: 40px auto; }
        .comment-box { background: #2a2a3c; padding: 15px; border-radius: 6px; margin-bottom: 15px; }
        .comment-header { font-weight: bold; margin-bottom: 10px; color: #aaa; }
        .tag-build { background-color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;}
        .tag-meta { background-color: #1565c0; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;}
        .tag-question { background-color: #e65100; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;}
        .tag-meme { background-color: #6a1b9a; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;}
        .back-link { display: inline-block; margin-bottom: 20px; color: #e94560; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        
        @keyframes heartPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
        .heart-pop {
            animation: heartPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>

<body>

    @include('partials.header')

    <main class="main-content-community">
        <div class="post-detail">
            <a href="{{ route('comunity.index') }}" class="back-link">← Volver a la Comunidad</a>
            <div class="post-detail-header">
                <span class="tag-{{ strtolower($post->category) }}">{{ strtoupper($post->category) }}</span>
                <h1>{{ $post->title }}</h1>
                <p style="color: #888; display: flex; align-items: center; gap: 8px;">
                    Publicado por 
                    <x-user-avatar :user="$post->user" size="24" style="border: 1px solid #ffcf00;" />
                    <a href="{{ $post->user ? url('/perfil/' . $post->user->id) : '#' }}" style="color: #ffcf00; font-weight: bold; text-decoration: none;">
                        {{ $post->user->username ?? 'Desconocido' }}
                    </a>
                    @if($post->user && $post->user->is_admin)
                        <span style="color: #1da1f2;" title="Verificado">☑️</span>
                    @endif
                    el {{ $post->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            
            <div class="post-detail-content">
                {{ $post->content }}
                
                @if($post->image_path)
                    <div style="margin-top: 20px; text-align: center;">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Imagen adjunta" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.5);">
                    </div>
                @endif
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #333; display: flex; align-items: center;">
                <form action="{{ route('comunity.like', $post->id) }}" method="POST" style="display: inline;" id="ajax-like-form">
                    @csrf
                    @php
                        $userLiked = auth()->check() ? $post->isLikedBy(auth()->user()) : false;
                    @endphp
                    <button type="submit" id="like-btn" style="background: transparent; border: 1px solid {{ $userLiked ? '#e94560' : '#555' }}; color: {{ $userLiked ? '#e94560' : '#ccc' }}; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-size: 1.1em; display: flex; align-items: center; gap: 8px; transition: border-color 0.2s, color 0.2s;">
                        <span id="like-icon" style="display: inline-block;">{{ $userLiked ? '❤️' : '🤍' }}</span>
                        <span id="like-count" style="font-weight: bold;">{{ $post->likes_count }}</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="comments-section">
            <h2>Comentarios ({{ $post->comments->sum(function($c) { return 1 + $c->replies->count(); }) }})</h2>
            
            @forelse($post->comments as $comment)
                @include('community.partials.comment', ['comment' => $comment, 'depth' => 0, 'submitUrl' => route('comunity.comment', $post->id)])
            @empty
                <p style="color: #888;">No hay comentarios todavía. ¡Sé el primero en comentar!</p>
            @endforelse

            <div class="comment-box" style="margin-top: 30px;">
                @auth
                    <h3>Deja un comentario</h3>
                    <form action="{{ route('comunity.comment', $post->id) }}" method="POST" class="ajax-comment-form">
                        @csrf
                        <input type="hidden" name="depth" value="0">
                        <textarea name="content" class="form-control" rows="3" placeholder="Escribe tu comentario aquí..." style="width: 100%; padding: 10px; background: #1e1e2e; color: #fff; border: 1px solid #444; border-radius: 4px; font-family: inherit; margin-bottom: 10px;" required></textarea>
                        <button type="submit" class="btn-submit" style="background: #e94560; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.2s;">Comentar</button>
                    </form>
                @else
                    <p>Debes <a href="{{ route('login') }}" style="color: #e94560;">iniciar sesión</a> para comentar.</p>
                @endauth
            </div>
        </div>
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

        const likeForm = document.getElementById('ajax-like-form');
        if (likeForm) {
            likeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('like-btn');
                const icon = document.getElementById('like-icon');
                const count = document.getElementById('like-count');
                
                // Add pop animation immediately
                icon.classList.remove('heart-pop');
                void icon.offsetWidth; // trigger reflow
                icon.classList.add('heart-pop');
                
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        count.innerText = data.likes_count;
                        if (data.is_liked) {
                            icon.innerText = '❤️';
                            btn.style.borderColor = '#e94560';
                            btn.style.color = '#e94560';
                        } else {
                            icon.innerText = '🤍';
                            btn.style.borderColor = '#555';
                            btn.style.color = '#ccc';
                        }
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(err => console.error('Error in like request', err));
            });
        }

        document.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('ajax-comment-form')) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('.btn-submit');
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
                            const rootContainer = document.querySelector('.comments-section');
                            // Find where to insert (before the 'Deja un comentario' box)
                            const commentBox = document.querySelector('.comment-box[style*="margin-top: 30px"]');
                            if (commentBox) {
                                commentBox.insertAdjacentHTML('beforebegin', data.html);
                            }
                            
                            // Remove "No hay comentarios" message if exists
                            const noMsg = rootContainer.querySelector('p[style*="color: #888"]');
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
