@extends('layouts.app')

@section('title', 'Login | Acceso de Usuarios | MEGABONK GUIDE')

@push('styles')
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
    </style>
@endpush

@section('content')
    <main class="main-content-auth">
        <div class="auth-card">
            <h1 class="auth-title">Inicia Sesión</h1>
            <p class="auth-subtitle">Accede a tus builds guardadas y a las funciones de la comunidad.</p>

            <form class="auth-form" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Nombre de Usuario o Email</label>
                    <input type="text" id="username" name="username" placeholder="Tu BonkID" 
                           class="@error('username') input-error @enderror" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="********" 
                           class="@error('password') input-error @enderror" required>
                    @error('password')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-auth">Acceder</button>
                </div>
            </form>

            <div class="auth-footer-links">
                <a href="#">¿Olvidaste tu Contraseña?</a>
                <span class="separator">|</span>
                <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
            </div>
        </div>
    </main>
@endsection