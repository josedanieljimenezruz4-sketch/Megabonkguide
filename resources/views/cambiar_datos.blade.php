@extends('layouts.app')

@section('title', 'Cambiar Datos | Gestión de Cuenta | MEGABONK GUIDE')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .error-feedback {
            color: #ff4d4d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
            font-weight: bold;
        }
        .input-error {
            border: 2px solid #ff4d4d !important;
            box-shadow: 0 0 5px rgba(255, 77, 77, 0.3) !important;
        }
        
        /* Contenedor del formulario con aire */
        .auth-form {
            max-width: 100%;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* Contenedor Relativo Estricto */
        .password-container {
            position: relative;
            width: 100%;
        }
        
        /* Input con Espacio */
        .password-container input {
            padding-right: 45px !important;
        }

        /* Inputs Premium con Box-Sizing vital */
        .auth-form .form-group input {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 14px 15px;
            color: #EAEAEA;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box; /* ESTO EVITA QUE SE SALGA DEL CONTENEDOR */
        }

        .auth-form .form-group input:focus {
            border-color: #41E8EF;
            background-color: #111;
            box-shadow: 0 0 10px rgba(65, 232, 239, 0.6); /* Glow neón en focus */
            outline: none;
        }
        
        /* Icono Absoluto Gaming */
        .password-toggle {
            position: absolute;
            right: 15px; /* Dentro del borde */
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            cursor: pointer;
            color: #41E8EF;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            text-shadow: 0 0 8px rgba(65, 232, 239, 0.8);
        }

        /* Botón Guardar Premium con Glow */
        .btn-primary-auth {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 1px solid #B965F0;
            border-radius: 8px;
            color: #EAEAEA;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            width: 100%;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn-primary-auth:hover {
            background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
            color: #fff;
            box-shadow: 0 0 15px rgba(185, 101, 240, 0.8), inset 0 0 10px rgba(185, 101, 240, 0.3); /* Glow fuerte */
            border-color: #d18cff;
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <main class="main-content-auth">
        <div class="auth-card">
            <h1 class="auth-title">Gestión de Cuenta</h1>
            <p class="auth-subtitle">Actualiza tu información personal y contraseña.</p>

            @if(session('success'))
                <div class="alert alert-success" style="color: #00ff88; background: rgba(0,255,136,0.1); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #00ff88;">
                    {{ session('success') }}
                </div>
            @endif

            <form class="auth-form" action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h2>Datos Personales</h2>

                <div class="form-group">
                    <label for="avatar">Avatar (Opcional - Máx 2MB)</label>
                    <input type="file" id="avatar" name="avatar" accept="image/jpeg, image/png, image/jpg, image/webp, image/gif"
                           class="@error('avatar') input-error @enderror" style="padding: 9px; cursor: pointer;">
                    @error('avatar')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}" 
                           class="@error('username') input-error @enderror" required>
                    @error('username')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                           class="@error('email') input-error @enderror" required>
                    @error('email')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <h2 class="form-section-title">Cambiar Contraseña</h2>

                <div class="form-group">
                    <label for="new-password">Nueva Contraseña</label>
                    <div class="password-container">
                        <input type="password" id="new-password" name="new_password"
                            placeholder="Dejar vacío si no deseas cambiarla" class="@error('new_password') input-error @enderror">
                        <i class="fas fa-eye password-toggle" id="toggle-new-password"></i>
                    </div>
                    @error('new_password')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new-password-confirm">Confirmar Contraseña</label>
                    <div class="password-container">
                        <input type="password" id="new-password-confirm" name="new_password_confirmation"
                            placeholder="Repite la nueva contraseña" class="@error('new_password_confirmation') input-error @enderror">
                        <i class="fas fa-eye password-toggle" id="toggle-confirm-password"></i>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-auth">Guardar Cambios</button>
                </div>
            </form>

            <div class="auth-footer-links" style="margin-top: 20px;">
                <a href="{{ route('profile') }}" class="btn-action btn-manage-data">← Volver al Perfil</a>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    function setupPasswordToggle(toggleId, inputId) {
        const toggleIcon = document.getElementById(toggleId);
        const passwordInput = document.getElementById(inputId);

        if (toggleIcon && passwordInput) {
            toggleIcon.addEventListener('click', function () {
                // Alternar tipo de input entre password y text
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Alternar clases del icono de FontAwesome
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle('toggle-new-password', 'new-password');
        setupPasswordToggle('toggle-confirm-password', 'new-password-confirm');
    });
</script>
@endpush
