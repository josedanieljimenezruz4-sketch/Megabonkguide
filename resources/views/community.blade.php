<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/community.css') }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <!-- =======================
         HEADER GLOBAL
    ======================= -->
    @include('partials.header')

    <!-- =======================
         CONTENIDO PRINCIPAL
    ======================= -->
    <main class="main-content-community" x-data="communityFilter()">

        <!-- Notificaciones de Error y Éxito -->
        @if ($errors->any())
            <div class="alert alert-danger" style="background: #ff4c4c; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success" style="background: #2e7d32; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Título de la Página -->
        <h1 class="page-title">🗣️ Portal de la Comunidad</h1>

        <p class="intro-text-community">
            Comparte tus builds, estrategias, ideas y memes con el resto de la comunidad Bonker. ¡Sé respetuoso y
            contribuye a la guía!
        </p>

        <!-- =======================
             FILTROS Y ACCIONES
        ======================= -->
        <section class="community-actions">
            <a href="#" class="btn-create-post" onclick="document.getElementById('createPostModal').style.display='block'; return false;">✍️ Publicar Nuevo Contenido</a>

            <div class="filter-controls" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <label for="sort-filter">Ordenar por:</label>
                <select id="sort-filter" class="custom-select" x-model="sort" @change="fetchPosts">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Más Reciente</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más Antiguo</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más Popular</option>
                    <option value="commented" {{ request('sort') == 'commented' ? 'selected' : '' }}>Más Comentado</option>
                </select>

                <label for="category-filter" style="margin-left: 10px;">Categoría:</label>
                <select id="category-filter" class="custom-select" x-model="category" @change="fetchPosts">
                    <option value="" {{ request('category') == '' ? 'selected' : '' }}>Todas</option>
                    <option value="question" {{ request('category') == 'question' ? 'selected' : '' }}>Preguntas</option>
                    <option value="build" {{ request('category') == 'build' ? 'selected' : '' }}>Builds</option>
                    <option value="meta" {{ request('category') == 'meta' ? 'selected' : '' }}>Estrategias</option>
                    <option value="meme" {{ request('category') == 'meme' ? 'selected' : '' }}>Memes</option>
                </select>
            </div>
        </section>

        <!-- =======================
             LISTADO DE POSTS
        ======================= -->
        <section class="posts-list" style="position: relative;">

            <!-- Spinner -->
            <div x-show="loading" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(30,30,46,0.5); z-index: 10; justify-content: center; align-items: center; border-radius: 8px;" :style="loading ? 'display: flex;' : 'display: none;'">
                <div class="neon-spinner"></div>
            </div>

            <div id="posts-container" :style="loading ? 'opacity: 0.5; transition: opacity 0.3s;' : 'opacity: 1; transition: opacity 0.3s;'">
                @include('community.partials.posts_list')
            </div>

        </section>

    </main>

    <!-- =======================
         MODAL CREAR POST
    ======================= -->
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
    
    /* Spinner Neon */
    .neon-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(233, 69, 96, 0.3);
        border-top: 4px solid #e94560;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

    <script>
    function communityFilter() {
        return {
            sort: '{{ request('sort', 'recent') }}',
            category: '{{ request('category', '') }}',
            loading: false,
            fetchPosts() {
                this.loading = true;
                
                const params = new URLSearchParams({
                    sort: this.sort,
                    category: this.category
                });
                
                fetch(window.location.pathname + '?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('posts-container').innerHTML = html;
                    this.loading = false;
                    
                    // Update URL silently
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + params.toString();
                    window.history.pushState({path: newUrl}, '', newUrl);
                })
                .catch(error => {
                    console.error("Error fetching posts:", error);
                    this.loading = false;
                });
            }
        }
    }
    </script>

    <!-- =======================
         FOOTER
    ======================= -->
    @include('partials.footer')

</body>

</html>
