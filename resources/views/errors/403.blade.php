@extends('layouts.app')

@section('title', 'Error 403 - Acceso Denegado')

@push('styles')
<style>
    .error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 20px;
    }
    .error-card {
        text-align: center;
        padding: 60px 40px;
        border-radius: 20px;
        max-width: 600px;
        width: 100%;
        border-color: rgba(255, 65, 108, 0.3) !important;
    }
    .error-card:hover {
        border-color: rgba(255, 65, 108, 0.8) !important;
        box-shadow: 0 0 30px rgba(255, 65, 108, 0.3) !important;
    }
    .error-title {
        font-size: 5rem;
        color: #ff416c; /* Rojo/Rosa neón */
        text-shadow: 0 0 15px rgba(255, 65, 108, 0.8), 0 0 30px rgba(255, 65, 108, 0.5);
        margin-top: 0;
        margin-bottom: 20px;
        line-height: 1;
    }
    .error-message {
        font-size: 1.4rem;
        color: var(--color-text-light);
        margin-bottom: 40px;
        line-height: 1.5;
    }
    .btn-neon-return {
        display: inline-block;
        padding: 15px 30px;
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--color-primary-accent);
        border: 2px solid var(--color-primary-accent);
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        background: transparent;
    }
    .btn-neon-return:hover {
        background-color: var(--color-primary-accent);
        color: #000;
        box-shadow: 0 0 20px rgba(65, 232, 239, 0.8);
        transform: translateY(-3px);
    }
    
    @media (max-width: 600px) {
        .error-title { font-size: 3.5rem; }
        .error-message { font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')
<main class="error-container">
    <div class="glass-card error-card">
        <h1 class="error-title">ERROR 403</h1>
        <p class="error-message">
            Acceso Denegado. No tienes permisos para acceder a estas funciones del sistema.
        </p>
        <a href="{{ url('/') }}" class="btn-neon-return">
            &larr; Volver al Inicio
        </a>
    </div>
</main>
@endsection
