<div class="comment-box" style="margin-left: {{ $depth > 0 ? 30 : 0 }}px; border-left: {{ $depth > 0 ? '2px solid #444' : 'none' }}; padding-left: {{ $depth > 0 ? '15px' : '15px' }}; margin-bottom: 10px; background: {{ $depth > 0 ? 'transparent' : '#2a2a3c' }}; padding-top: {{ $depth > 0 ? '10px' : '15px' }};">
    <div class="comment-header" style="margin-bottom: 8px; display: flex; justify-content: space-between; align-items: flex-start;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <x-user-avatar :user="$comment->user" size="24" />
            <strong style="color: #ffcf00; font-size: 1.05em; display: flex; align-items: center;">
                {{ $comment->user->username ?? 'Usuario Anónimo' }}
                @if($comment->user && $comment->user->is_admin)
                    <span style="color: #1da1f2; margin-left: 2px;" title="Personal Oficial">☑️</span>
                @endif
                @if($comment->user && $comment->user->discord_id)
                    <span style="color: #5865F2; margin-left: 4px; display: inline-flex; align-items: center;" title="Miembro de Discord Oficial">
                        <svg width="14" height="14" viewBox="0 0 127.14 96.36" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a67.58,67.58,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1,105.25,105.25,0,0,0,32.19-16.14c2.64-27.38-4.51-51.11-18.9-72.15ZM42.56,65.36c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C52.36,60.53,48.06,65.36,42.56,65.36Zm42,0c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C94.41,60.53,90.1,65.36,84.56,65.36Z"/></svg>
                    </span>
                @endif
            </strong>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #aaa; font-size: 0.85em;">{{ $comment->created_at->diffForHumans() }}</span>
            @if(auth()->check() && auth()->user()->is_admin)
                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este comentario?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: transparent; border: none; color: #ff4757; cursor: pointer; padding: 0;" title="Eliminar Comentario">🗑️</button>
                </form>
            @endif
        </div>
    </div>
    <div class="comment-body" style="color: #eee; margin-bottom: 10px;">
        {{ $comment->content }}
    </div>
    <div class="comment-actions" style="display: flex; gap: 15px; align-items: center; margin-bottom: 10px;">
        @auth
            <button class="btn-reply" onclick="document.getElementById('reply-form-{{ $comment->id }}').style.display = 'block'" style="background: transparent; border: none; color: #ffcf00; cursor: pointer; font-size: 0.85em; padding: 0; display: flex; align-items: center; gap: 5px; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#ffcf00'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 10 20 15 15 20"></polyline><path d="M4 4v7a4 4 0 0 0 4 4h12"></path></svg>
                Responder
            </button>
        @endauth

        @if($comment->replies && $comment->replies->count() > 0)
            <button type="button" onclick="toggleReplies({{ $comment->id }})" id="toggle-replies-btn-{{ $comment->id }}" style="background: transparent; border: none; color: #aaa; cursor: pointer; font-size: 0.85em; padding: 0; display: flex; align-items: center; gap: 5px; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">
                <span class="arrow-icon" style="transition: transform 0.2s; display: inline-block;">▼</span>
                <span class="btn-text">Ver respuestas (<span class="replies-count-{{ $comment->id }}">{{ $comment->replies->count() }}</span>)</span>
            </button>
        @else
            <button type="button" onclick="toggleReplies({{ $comment->id }})" id="toggle-replies-btn-{{ $comment->id }}" style="background: transparent; border: none; color: #aaa; cursor: pointer; font-size: 0.85em; padding: 0; display: none; align-items: center; gap: 5px; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">
                <span class="arrow-icon" style="transition: transform 0.2s; display: inline-block;">▼</span>
                <span class="btn-text">Ver respuestas (<span class="replies-count-{{ $comment->id }}">0</span>)</span>
            </button>
        @endif
    </div>

    @auth
    <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 10px; margin-bottom: 15px; padding: 10px; background: #232333; border-radius: 6px; border: 1px solid #333;">
        <form action="{{ $submitUrl }}" method="POST" class="ajax-comment-form">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <input type="hidden" name="depth" value="{{ $depth + 1 }}">
            <textarea name="content" class="form-control" rows="2" placeholder="Escribe tu respuesta..." required style="width: 100%; padding: 8px; background: #1e1e2e; color: #fff; border: 1px solid #444; border-radius: 4px; font-family: inherit; margin-bottom: 8px; box-sizing: border-box;"></textarea>
            <div>
                <button type="submit" class="btn-submit" style="background: #e94560; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.8em; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">Enviar</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('reply-form-{{ $comment->id }}').style.display = 'none'" style="background: transparent; color: #aaa; border: 1px solid #444; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-left: 5px; font-size: 0.8em; transition: 0.2s;" onmouseover="this.style.color='#fff'; this.style.borderColor='#666';" onmouseout="this.style.color='#aaa'; this.style.borderColor='#444';">Cancelar</button>
            </div>
        </form>
    </div>
    @endauth

    <div id="replies-{{ $comment->id }}" class="replies-container" style="margin-top: 10px; display: {{ ($comment->replies && $comment->replies->count() > 0) ? 'none' : 'block' }};">
        @if($comment->replies)
            @foreach($comment->replies as $reply)
                @include('community.partials.comment', ['comment' => $reply, 'depth' => $depth + 1, 'submitUrl' => $submitUrl])
            @endforeach
        @endif
    </div>
</div>
