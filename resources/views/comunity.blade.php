<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/comunity.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <main class="main-content-community">

        <h1 class="page-title">🗣️ Portal de la Comunidad</h1>

        <p class="intro-text-community">
            Comparte tus builds, estrategias, ideas y memes con el resto de la comunidad Bonker. ¡Sé respetuoso y
            contribuye a la guía!
        </p>

        <section class="community-actions">
            <a href="#" class="btn-create-post" onclick="document.getElementById('createPostModal').style.display='block'; return false;">✍️ Publicar Nuevo Contenido</a>

            <div class="filter-controls">
                <label for="category-filter">Filtrar por:</label>
                <select id="category-filter" class="custom-select" onchange="window.location.href='?filter='+this.value">
                    <option value="recent" {{ request('filter') == 'recent' ? 'selected' : '' }}>Más Reciente</option>
                    <option value="oldest" {{ request('filter') == 'oldest' ? 'selected' : '' }}>Más Antiguo</option>
                    <option value="popular" {{ request('filter') == 'popular' ? 'selected' : '' }}>Más Popular</option>
                    <option value="build" {{ request('filter') == 'build' ? 'selected' : '' }}>Builds</option>
                    <option value="meta" {{ request('filter') == 'meta' ? 'selected' : '' }}>Meta & Estrategia</option>
                    <option value="question" {{ request('filter') == 'question' ? 'selected' : '' }}>Preguntas</option>
                    <option value="meme" {{ request('filter') == 'meme' ? 'selected' : '' }}>Memes</option>
                </select>
            </div>
        </section>

        <section class="posts-list">

            @forelse($posts as $post)
            <div class="post-card glow-{{ strtolower($post->category) }}">
                <div class="post-header">
                    <span class="post-category tag-{{ strtolower($post->category) }}">{{ strtoupper($post->category) }}</span>
                    <h3><a href="{{ route('comunity.show', $post->id) }}" class="post-title-link">{{ $post->title }}</a></h3>
                    <div class="post-meta" style="display: flex; align-items: center; gap: 10px; margin-top: 10px; color: #aaa; font-size: 0.9em;">
                        <x-user-avatar :user="$post->user" size="40" class="post-author-avatar" style="border: 2px solid #ffcf00;" />
                        <span style="display: flex; align-items: center; gap: 4px;">
                            Publicado por 
                            <a href="{{ $post->user ? url('/perfil/' . $post->user->id) : '#' }}" style="color: #ffcf00; font-weight: bold; text-decoration: none;">
                                {{ $post->user->username ?? 'Desconocido' }}
                            </a>
                            @if($post->user && $post->user->is_admin)
                                <span style="color: #1da1f2;" title="Verificado">☑️</span>
                            @endif
                            hace {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                
                @if($post->image_path)
                    <div style="margin-top: 10px; margin-bottom: 10px; height: 150px; overflow: hidden; border-radius: 6px;">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Imagen" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
                    </div>
                @endif

                <p class="post-summary">
                    {{ Str::limit($post->content, 150) }}
                </p>
                <div class="post-footer">
                    <span class="stats likes">❤️ {{ $post->likes_count }}</span>
                    <span class="stats comments">💬 {{ $post->comments_count }} Comentarios</span>
                    <a href="{{ route('comunity.show', $post->id) }}" class="view-post-link">Ver Discusión →</a>
                </div>
            </div>
            @empty
            <p class="empty-state">No hay publicaciones disponibles.</p>
            @endforelse

            <div class="pagination-wrapper" style="margin-top: 20px;">
                {{ $posts->appends(['filter' => request('filter')])->links() }}
            </div>

        </section>

    </main>

    @auth
    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('createPostModal').style.display='none'">&times;</span>
            <h2>Crear Nueva Publicación</h2>
            <form action="{{ route('comunity.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" id="title" name="title" required class="form-control" maxlength="255">
                </div>
                <div class="form-group">
                    <label for="category">Categoría</label>
                    <select id="category" name="category" required class="form-control">
                        <option value="build">Build</option>
                        <option value="meta">Meta & Estrategia</option>
                        <option value="question">Pregunta</option>
                        <option value="meme">Meme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Adjuntar Imagen (Opcional)</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" required class="form-control" rows="5"></textarea>
                </div>
                <button type="submit" class="btn-submit">Publicar</button>
            </form>
        </div>
    </div>
    @else
    <div id="createPostModal" class="modal">
        <div class="modal-content text-center">
            <span class="close" onclick="document.getElementById('createPostModal').style.display='none'">&times;</span>
            <h2>Debes iniciar sesión</h2>
            <p>Necesitas una cuenta para publicar en la comunidad.</p>
            <br>
            <a href="{{ route('login') }}" class="btn-submit">Ir a Login</a>
        </div>
    </div>
    @endauth

    <style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); }
    .modal-content { background-color: #1e1e2e; margin: 10% auto; padding: 20px; border: 1px solid #333; width: 80%; max-width: 500px; border-radius: 8px; color: #fff; }
    .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: #fff; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-control { width: 100%; padding: 10px; background: #2a2a3c; border: 1px solid #444; color: #fff; border-radius: 4px; font-family: inherit; }
    .btn-submit { background: #e94560; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-weight: bold; display: inline-block; text-decoration: none; transition: 0.2s; }
    .btn-submit:hover { background: #d03050; }
    .text-center { text-align: center; }
    .tag-build { background-color: #2e7d32; }
    .tag-meta { background-color: #fbc02d; color: #000; }
    .tag-question { background-color: #e65100; }
    .tag-meme { background-color: #6a1b9a; }
    .pagination-wrapper nav { display: flex; justify-content: center; }
    .pagination-wrapper nav svg { max-width: 20px; }
    
    .post-title-link { color: inherit; text-decoration: none; transition: color 0.2s; }
    .post-title-link:hover { color: #e94560; }
    
    .post-card { background: #1e1e2e; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #333; transition: transform 0.2s, box-shadow 0.3s, border-color 0.3s; }
    .post-card:hover { transform: translateY(-3px); }
    
    .glow-build:hover { border-color: #2e7d32; box-shadow: 0 0 15px rgba(46, 125, 50, 0.4); }
    .glow-meta:hover { border-color: #fbc02d; box-shadow: 0 0 15px rgba(251, 192, 45, 0.4); }
    .glow-question:hover { border-color: #e65100; box-shadow: 0 0 15px rgba(230, 81, 0, 0.4); }
    .glow-meme:hover { border-color: #6a1b9a; box-shadow: 0 0 15px rgba(106, 27, 154, 0.4); }
    </style>

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

</body>

</html>