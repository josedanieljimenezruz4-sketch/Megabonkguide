@extends('layouts.app')

@section('title', 'Error 404 - Página no encontrada')

@section('content')
<main class="error-container">
    <div class="glass-card error-card error-card--notfound">
        <h1 class="error-title error-title--cyan">404</h1>
        <h2 class="error-subtitle">¡El Megabonk ha golpeado esta página demasiado fuerte!</h2>
        <p class="error-message error-message--muted">
            Lo sentimos, pero no encontramos la página que estabas buscando. Quizás el rastro que seguías se ha cortado.
        </p>
        <a href="{{ url('/') }}" class="btn-neon-return">
            &larr; Volver a la Base (Inicio)
        </a>
    </div>
</main>
@endsection
