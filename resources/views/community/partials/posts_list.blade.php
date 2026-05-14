@forelse($posts as $post)
<div class="post-card glow-{{ strtolower($post->category) }}">
    <div class="post-header">
        <span class="post-category tag-{{ strtolower($post->category) }}">{{ strtoupper($post->category) }}</span>
        <h3><a href="{{ route('comunity.show', $post->id) }}" class="post-title-link break-words whitespace-normal max-w-full" style="display: block;">{{ $post->title }}</a></h3>
        <div class="post-meta" style="display: flex; align-items: center; gap: 10px; margin-top: 10px; color: #aaa; font-size: 0.8rem;">
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

    <p class="post-summary break-words whitespace-normal max-w-full" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
        {{ $post->content }}
    </p>
    <div class="post-footer">
        <span class="stats likes">❤️ {{ $post->likes_count }}</span>
        <span class="stats comments" style="display: flex; align-items: center; gap: 5px;">💬 {{ $post->comments_count }} Comentarios</span>
        <a href="{{ route('comunity.show', $post->id) }}" class="view-post-link">Ver Discusión →</a>
    </div>
</div>
@empty
<p class="empty-state">No hay publicaciones disponibles.</p>
@endforelse

<!-- =======================
     PAGINACIÓN
======================= -->
<div class="pagination-wrapper" style="margin-top: 20px;">
    {{ $posts->appends(['filter' => request('filter')])->links() }}
</div>
