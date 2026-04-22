<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tierList->titulo }} | Tier List de la Comunidad</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('iconotlabaho.webp') }}" type="image/x-icon">
</head>

<body>
    @include('partials.header')

    <main class="main-content-tierlist">

        <h1 class="page-title">{{ $tierList->titulo }}</h1>
        
        <p class="intro-text-tierlist" style="text-align: center; color: #ffcf00;">
            Creada por: <strong>{{ $tierList->user->username ?? 'Usuario Anónimo' }}</strong>
            @if($tierList->user && $tierList->user->is_admin)
                <span style="color: #1da1f2; margin-left: 2px;" data-tippy-content="Tier List Oficial de Megabonk Guide">☑️</span>
            @endif
            | Categoría: {{ ucfirst($tierList->categoria) }}
        </p>

        @if($tierList->descripcion)
        <div style="background: #2c2f33; padding: 20px; border-radius: 10px; margin-bottom: 30px; margin-inline: auto; max-width: 800px;">
            <p style="margin: 0; color: #ddd; text-align: center; font-style: italic;">
                "{{ $tierList->descripcion }}"
            </p>
        </div>
        @endif

        <div class="tierlist-container">
            <table>
                <thead>
                    <tr>
                        <th class="tier-rank">RANGO</th>
                        <th>UNIDADES</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
                    @endphp

                    @foreach($ranksOrder as $rank)
                        @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                            <tr class="tier-{{ strtolower($rank) }}">
                                <td class="tier-rank">{{ $rank }}</td>
                                <td>
                                    <div class="tier-items-list"
                                        style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                                        @foreach($itemsByRank[$rank] as $item)
                                            <div class="tier-item" data-tippy-content="{{ $item->description ?? 'Sin descripción.' }}"
                                                style="display: flex; flex-direction: column; align-items: center; width: 80px; text-align: center;">
                                                @php
                                                    $imageSrc = asset('images/' . $item->image_path);
                                                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                                                        $imageSrc = asset('storage/' . $item->image_path);
                                                    }
                                                @endphp
                                                @if($item->image_path)
                                                    <img src="{{ $imageSrc }}" alt="{{ $item->name }}"
                                                        title="{{ $item->name }}"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                                        style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                                                @else
                                                    <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}"
                                                        title="{{ $item->name }}"
                                                        style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                                                @endif
                                                <span style="font-size: 0.8em; margin-top: 5px; line-height: 1.1;">{{ $item->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="comments-section" style="margin-top: 50px; background: #1a1a1a; padding: 20px; border-radius: 10px; max-width: 800px; margin-inline: auto; border: 1px solid #333; text-align: left;">
            <h2 style="border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 0;">💬 Comentarios</h2>

            <div class="comments-list" style="margin-top: 20px;">
                @forelse($tierList->comments as $comment)
                    <div class="comment-thread" style="margin-bottom: 15px;">
                        <div class="comment" style="background: #2c2f33; padding: 15px; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="color: #ffcf00;">
                                    {{ $comment->user->username ?? 'Usuario Anónimo' }}
                                    @if($comment->user && $comment->user->is_admin)
                                        <span style="color: #1da1f2; margin-left: 2px;" data-tippy-content="Personal Oficial de Megabonk Guide">☑️</span>
                                    @endif
                                    @if($comment->user && $comment->user->discord_id)
                                        <span style="color: #5865F2; margin-left: 4px; display: inline-flex; align-items: center;" data-tippy-content="Miembro de Discord Oficial">
                                            <svg width="14" height="14" viewBox="0 0 127.14 96.36" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a67.58,67.58,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1,105.25,105.25,0,0,0,32.19-16.14c2.64-27.38-4.51-51.11-18.9-72.15ZM42.56,65.36c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C52.36,60.53,48.06,65.36,42.56,65.36Zm42,0c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C94.41,60.53,90.1,65.36,84.56,65.36Z"/></svg>
                                        </span>
                                    @endif
                                </strong>
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
                            <p style="margin: 0 0 10px 0; color: #ddd; line-height: 1.4;">{{ $comment->content }}</p>
                            <div style="display: flex; gap: 15px; align-items: center;">
                                @auth
                                    <button type="button" onclick="toggleReplyForm({{ $comment->id }})" style="background: transparent; border: none; color: #ffcf00; cursor: pointer; font-size: 0.85em; padding: 0; display: flex; align-items: center; gap: 5px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 10 20 15 15 20"></polyline><path d="M4 4v7a4 4 0 0 0 4 4h12"></path></svg>
                                        Responder
                                    </button>
                                @endauth

                                @if($comment->replies && $comment->replies->count() > 0)
                                    <button type="button" onclick="toggleReplies({{ $comment->id }})" id="toggle-replies-btn-{{ $comment->id }}" style="background: transparent; border: none; color: #aaa; cursor: pointer; font-size: 0.85em; padding: 0; display: flex; align-items: center; gap: 5px; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">
                                        <span class="arrow-icon" style="transition: transform 0.2s; display: inline-block;">▼</span>
                                        <span class="btn-text">Ver respuestas ({{ $comment->replies->count() }})</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Formulario de Respuesta Oculto -->
                        @auth
                            <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 10px; margin-left: 40px;">
                                <form action="{{ route('community-tierlists.comment', $tierList->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div style="margin-bottom: 10px;">
                                        <textarea name="content" rows="2" placeholder="Escribe tu respuesta..." required style="width: 100%; background: #222; border: 1px solid #444; color: white; padding: 10px; border-radius: 8px; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <button type="submit" class="btn btn-primary-link" style="border: none; cursor: pointer; padding: 5px 15px; font-size: 0.9em;">Enviar</button>
                                        <button type="button" onclick="toggleReplyForm({{ $comment->id }})" style="background: transparent; border: 1px solid #444; color: #aaa; cursor: pointer; padding: 5px 15px; font-size: 0.9em; border-radius: 5px;">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        @endauth

                        <!-- Respuestas -->
                        @if($comment->replies && $comment->replies->count() > 0)
                            <div id="replies-{{ $comment->id }}" class="replies" style="display: none; margin-left: 40px; border-left: 2px solid #444; padding-left: 15px; margin-top: 15px;">
                                @foreach($comment->replies as $reply)
                                    <div class="comment reply" style="background: #25272a; padding: 12px; border-radius: 8px; margin-bottom: 10px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <strong style="color: #ffcf00; font-size: 0.95em;">
                                                {{ $reply->user->username ?? 'Usuario Anónimo' }}
                                                @if($reply->user && $reply->user->is_admin)
                                                    <span style="color: #1da1f2; margin-left: 2px;" data-tippy-content="Personal Oficial de Megabonk Guide">☑️</span>
                                                @endif
                                                @if($reply->user && $reply->user->discord_id)
                                                    <span style="color: #5865F2; margin-left: 4px; display: inline-flex; align-items: center;" data-tippy-content="Miembro de Discord Oficial">
                                                        <svg width="14" height="14" viewBox="0 0 127.14 96.36" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a67.58,67.58,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1,105.25,105.25,0,0,0,32.19-16.14c2.64-27.38-4.51-51.11-18.9-72.15ZM42.56,65.36c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C52.36,60.53,48.06,65.36,42.56,65.36Zm42,0c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C94.41,60.53,90.1,65.36,84.56,65.36Z"/></svg>
                                                    </span>
                                                @endif
                                            </strong>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <span style="color: #aaa; font-size: 0.8em;">{{ $reply->created_at->diffForHumans() }}</span>
                                                @if(auth()->check() && auth()->user()->is_admin)
                                                    <form action="{{ route('admin.comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar esta respuesta?');" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="background: transparent; border: none; color: #ff4757; cursor: pointer; padding: 0; font-size: 0.9em;" title="Eliminar Respuesta">🗑️</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <p style="margin: 0; color: #ccc; line-height: 1.4; font-size: 0.95em;">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color: #aaa; text-align: center; font-style: italic;">No hay comentarios aún. ¡Sé el primero en comentar!</p>
                @endforelse
            </div>

            <div class="comment-form" style="margin-top: 30px; border-top: 1px solid #333; padding-top: 20px;">
                @auth
                    <form action="{{ route('community-tierlists.comment', $tierList->id) }}" method="POST">
                        @csrf
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

    <footer class="main-footer" style="margin-top: 50px;">
        <div class="footer-copy">
            &copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.
        </div>
    </footer>

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
                btnText.innerText = btnText.innerText.replace('Ver', 'Ocultar');
            } else {
                repliesDiv.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
                btnText.innerText = btnText.innerText.replace('Ocultar', 'Ver');
            }
        }
    </script>
</body>
</html>
