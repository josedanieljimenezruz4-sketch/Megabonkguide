@extends('layouts.app')

@section('title', 'Error 404 - Página no encontrada')

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
        max-width: 650px;
        width: 100%;
        border-color: rgba(65, 232, 239, 0.3) !important; /* Cyan border */
    }
    .error-card:hover {
        border-color: rgba(65, 232, 239, 0.8) !important;
        box-shadow: 0 0 30px rgba(65, 232, 239, 0.3) !important;
    }
    .error-title {
        font-size: 6rem;
        color: var(--color-primary-accent); /* Cyan neón */
        text-shadow: 0 0 15px rgba(65, 232, 239, 0.8), 0 0 30px rgba(65, 232, 239, 0.5);
        margin-top: 0;
        margin-bottom: 10px;
        line-height: 1;
    }
    .error-subtitle {
        font-size: 2rem;
        color: var(--color-text-light);
        margin-bottom: 20px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .error-message {
        font-size: 1.3rem;
        color: var(--color-text-secondary);
        margin-bottom: 40px;
        line-height: 1.6;
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
        .error-title { font-size: 4rem; }
        .error-subtitle { font-size: 1.5rem; }
        .error-message { font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')
<main class="error-container">
    <div class="glass-card error-card">
        <h1 class="error-title">404</h1>
        <h2 class="error-subtitle">¡El Megabonk ha golpeado esta página demasiado fuerte!</h2>
        <p class="error-message">
            Lo sentimos, pero no encontramos la página que estabas buscando. Quizás el rastro que seguías se ha cortado.
        </p>
        <a href="{{ url('/') }}" class="btn-neon-return">
            &larr; Volver a la Base (Inicio)
        </a>
    </div>
</main>
@endsection
