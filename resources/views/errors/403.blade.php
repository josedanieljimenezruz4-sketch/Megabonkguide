@extends('layouts.app')

@section('title', 'Error 403 - Acceso Denegado')

@section('content')
<main class="error-container">
    <div class="glass-card error-card error-card--forbidden">
        <h1 class="error-title error-title--red">ERROR 403</h1>
        <p class="error-message">
            Acceso Denegado. No tienes permisos para acceder a estas funciones del sistema.
        </p>
        <a href="{{ url('/') }}" class="btn-neon-return">
            &larr; Volver al Inicio
        </a>
    </div>
</main>
@endsection
