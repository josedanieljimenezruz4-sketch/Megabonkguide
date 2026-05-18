@extends('layouts.app')

@section('title', 'Mi Perfil | MEGABONK GUIDE')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-3xl font-bold text-white mb-8 text-center" style="font-size: 2.5em; margin-bottom: 30px;">Mi Perfil
            Personal</h1>

        <div class="profile-card"
            style="background: #1e1e2e; border: 1px solid #333; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">

            <!-- Avatar Wrapper with Hover to Edit -->
            <div class="avatar-wrapper"
                style="position: relative; width: 200px; height: 200px; margin: 0 auto 20px; border-radius: 50%; border: 4px solid {{ auth()->id() == $user->id ? '#e94560' : '#ffcf00' }}; overflow: hidden; {{ auth()->id() == $user->id ? 'cursor: pointer;' : '' }}"
                {!! auth()->id() == $user->id ? 'onclick="document.getElementById(\'avatar-input\').click()"' : '' !!}>

                <img id="profile-avatar-img"
                    src="{{ $user->avatar && str_starts_with($user->avatar, 'http') ? $user->avatar : ($user->avatar ? asset('storage/avatars/' . $user->avatar) : asset('images/default-avatar.png')) }}"
                    alt="Avatar de {{ $user->username }}" style="width: 100%; height: 100%; object-fit: cover;">

                @if(auth()->id() == $user->id)
                    <div class="avatar-overlay"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span style="color: #fff; font-weight: bold; text-shadow: 1px 1px 2px #000;">Cambiar Foto</span>
                    </div>

                    <!-- Hidden File Input -->
                    <input type="file" id="avatar-input" accept="image/*" style="display: none;" onchange="uploadAvatar(this)">
                @endif
            </div>

            <h2 class="text-2xl text-white font-bold" style="color: #ffcf00; font-size: 2em; margin-bottom: 5px;">
                {{ $user->username ?? 'Desconocido' }}
                @if($user->is_admin)
                    <span style="color: #1da1f2; font-size: 0.8em; vertical-align: middle;" title="Verificado">☑️</span>
                @endif
            </h2>
            @if(auth()->id() == $user->id)
                <p class="text-gray-400" style="color: #aaa; font-size: 1.1em; margin-bottom: 30px;">{{ $user->email }}</p>
            @else
                <p class="text-gray-400" style="color: #aaa; font-size: 1.1em; margin-bottom: 30px;">Registrado el
                    {{ $user->created_at->format('d/m/Y') }}
                </p>
            @endif

            <div style="margin-bottom: 30px;">
                <div
                    style="width: 100%; %display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="color: #ffcf00; font-weight: bold; font-size: 1.1em;">{{ $progreso }}% de Unlocks
                        completados</span>
                    <span style="color: #aaa; font-size: 0.9em;">🏆 {{ $builds->count() }} Builds creadas | 📂
                        {{ $tierLists->count() }} Tier Lists</span>
                </div>
                <div
                    style="background: #2a2a3c; border-radius: 9999px; height: 12px; width: 100%; overflow: hidden; border: 1px solid #444;">
                    <div
                        style="height: 100%; width: {{ $progreso }}%; background: linear-gradient(90deg, #ff416c, #b965f0); box-shadow: 0 0 10px rgba(255,0,255,0.5); border-radius: 9999px; transition: width 0.5s ease;">
                    </div>
                </div>
            </div>

            @if(auth()->id() == $user->id)
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="{{ route('profile.settings') }}" class="btn-secondary"
                        style="background: transparent; color: #fff; border: 1px solid #e94560; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; transition: 0.2s;">⚙️
                        Editar Configuración</a>
                    <a href="{{ route('inventory') }}" class="btn-primary"
                        style="background: #e94560; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; transition: 0.2s;">🎒
                        Mi Inventario</a>
                </div>
            @endif
        </div>

        <!-- Secciones de Actividad -->
        <div style="margin-top: 40px;">
            <h3
                style="color: #fff; font-size: 1.5em; border-bottom: 2px solid #e94560; padding-bottom: 10px; margin-bottom: 20px;">
                Mis Builds Creadas</h3>
            @if($builds->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                    @foreach($builds as $build)
                        <div style="position: relative;">
                            <a href="{{ route('builds.show', $build->id) }}" style="text-decoration: none; color: inherit;">
                                <div style="background: #1e1e2e; padding: 15px; border-radius: 8px; border-left: 4px solid #2e7d32; transition: transform 0.2s; border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;"
                                    onmouseover="this.style.transform='translateY(-3px)'"
                                    onmouseout="this.style.transform='translateY(0)'">
                                    <h4 style="color: #fff; margin: 0 0 5px 0;">{{ $build->name ?? 'Build sin título' }}</h4>
                                    <span style="color: #aaa; font-size: 0.85em;">{{ $build->created_at->format('d/m/Y') }}</span>
                                </div>
                            </a>
                            @if(auth()->id() == $user->id)
                                <a href="{{ route('builds.edit', $build->id) }}"
                                    style="position: absolute; top: 10px; right: 10px; background: rgba(255, 255, 255, 0.1); color: #ffcf00; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8em; border: 1px solid #ffcf00;">✏️
                                    Editar</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #888; font-style: italic;">Aún no has creado ninguna build.</p>
            @endif
        </div>

        <div style="margin-top: 40px; margin-bottom: 40px;">
            <h3
                style="color: #fff; font-size: 1.5em; border-bottom: 2px solid #ffcf00; padding-bottom: 10px; margin-bottom: 20px;">
                Mis Tier Lists</h3>
            @if($tierLists->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                    @foreach($tierLists as $tl)
                        <div style="position: relative;">
                            <a href="{{ route('community-tierlists.show', $tl->id) }}"
                                style="text-decoration: none; color: inherit;">
                                <div style="background: #1e1e2e; padding: 15px; border-radius: 8px; border-left: 4px solid #ffcf00; transition: transform 0.2s; border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;"
                                    onmouseover="this.style.transform='translateY(-3px)'"
                                    onmouseout="this.style.transform='translateY(0)'">
                                    <h4 style="color: #fff; margin: 0 0 5px 0;">{{ $tl->titulo }}</h4>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.85em; color: #aaa;">
                                        <span>{{ ucfirst($tl->categoria) }}</span>
                                        <span>{{ $tl->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </a>
                            @if(auth()->id() == $user->id)
                                <a href="{{ route('community-tierlists.edit', $tl->id) }}"
                                    style="position: absolute; top: 10px; right: 10px; background: rgba(255, 255, 255, 0.1); color: #ffcf00; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8em; border: 1px solid #ffcf00;">✏️
                                    Editar</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #888; font-style: italic;">Aún no has publicado ninguna Tier List.</p>
            @endif
        </div>
    </div>

    <style>
        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1 !important;
        }

        .btn-primary:hover {
            background: #d03050;
        }

        .btn-secondary:hover {
            background: #e94560;
        }
    </style>

    <script>
        function uploadAvatar(input) {
            if (input.files && input.files[0]) {
                let formData = new FormData();
                formData.append('avatar', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                // Show loading state
                const overlay = document.querySelector('.avatar-overlay');
                overlay.innerHTML = '<span style="color: #fff; font-weight: bold;">Subiendo...</span>';
                overlay.style.opacity = '1';

                fetch('{{ route("profile.avatar.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('profile-avatar-img').src = data.avatar_url;
                            // Also update the navbar avatar
                            const navAvatar = document.querySelector('.profile-avatar');
                            if (navAvatar) navAvatar.src = data.avatar_url;
                            if (window.showToast) window.showToast('¡Avatar actualizado!');
                        } else {
                            alert(data.message || 'Error al subir la imagen.');
                        }
                        resetOverlay();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de conexión al subir la imagen.');
                        resetOverlay();
                    });
            }
        }

        function resetOverlay() {
            const overlay = document.querySelector('.avatar-overlay');
            overlay.innerHTML = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg><span style="color: #fff; font-weight: bold; text-shadow: 1px 1px 2px #000;">Cambiar Foto</span>';
            overlay.style.opacity = '0';
        }
    </script>
@endsection