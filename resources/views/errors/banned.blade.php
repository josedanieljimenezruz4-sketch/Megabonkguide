@extends('layouts.app')

@section('title', 'Cuenta Suspendida | MEGABONK GUIDE')

@section('content')
<main class="error-container">
    <div class="glass-card error-card banned-card">

        {{-- Título de prisión --}}
        <h1 class="error-title banned-title">🔒 HAS SIDO BANEADO</h1>

        <p class="error-message">
            Tu cuenta ha sido suspendida temporalmente por violar las normas del Megabonk.
        </p>

        {{-- Contador de tiempo restante --}}
        @php
            $baneadoHasta = auth()->user()->banned_until;
            $esPermanente = $baneadoHasta->diffInYears(now()) > 50;
        @endphp

        @if($esPermanente)
            <div class="banned-countdown-container">
                <p class="banned-permanent-label">⛔ SUSPENSIÓN PERMANENTE</p>
                <p class="banned-permanent-message">Tu cuenta ha sido deshabilitada de forma indefinida.</p>
            </div>
        @else
            <div class="banned-countdown-container" x-data="contadorBaneo('{{ $baneadoHasta->toIso8601String() }}')" x-init="iniciarContador()">
                <p class="banned-countdown-label">Tiempo restante de suspensión:</p>
                <div class="banned-clock">
                    <div class="banned-clock-unit">
                        <span class="banned-clock-value" x-text="dias">00</span>
                        <span class="banned-clock-label">Días</span>
                    </div>
                    <span class="banned-clock-separator">:</span>
                    <div class="banned-clock-unit">
                        <span class="banned-clock-value" x-text="horas">00</span>
                        <span class="banned-clock-label">Horas</span>
                    </div>
                    <span class="banned-clock-separator">:</span>
                    <div class="banned-clock-unit">
                        <span class="banned-clock-value" x-text="minutos">00</span>
                        <span class="banned-clock-label">Minutos</span>
                    </div>
                    <span class="banned-clock-separator">:</span>
                    <div class="banned-clock-unit">
                        <span class="banned-clock-value" x-text="segundos">00</span>
                        <span class="banned-clock-label">Segundos</span>
                    </div>
                </div>

                {{-- Botón de recarga cuando termine el baneo --}}
                <div x-show="expirado" x-transition class="banned-expired-actions">
                    <p class="banned-expired-message">✅ ¡Tu suspensión ha terminado!</p>
                    <a href="{{ url('/') }}" class="btn-neon-return">🔓 Recargar y volver a entrar</a>
                </div>
            </div>
        @endif

        {{-- Botón de Cerrar Sesión --}}
        <div class="banned-logout-container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-banned-logout">Cerrar Sesión</button>
            </form>
        </div>

    </div>
</main>
@endsection

@push('scripts')
{{-- Alpine.js para el contador en tiempo real --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    /**
     * Controlador del tiempo de baneo. 
     * Se encarga de hacer la cuenta atrás y revelar el botón de recarga.
     */
    function contadorBaneo(fechaBaneoISO) {
        return {
            dias: '00',
            horas: '00',
            minutos: '00',
            segundos: '00',
            expirado: false,
            intervalo: null,

            iniciarContador() {
                const objetivo = new Date(fechaBaneoISO).getTime();

                this.intervalo = setInterval(() => {
                    const ahora = Date.now();
                    const diferencia = objetivo - ahora;

                    if (diferencia <= 0) {
                        // El baneo ha expirado: limpiamos variables y detenemos el reloj
                        this.dias = '00';
                        this.horas = '00';
                        this.minutos = '00';
                        this.segundos = '00';
                        this.expirado = true;
                        clearInterval(this.intervalo);
                        return;
                    }

                    // Calcular unidades de tiempo restantes
                    const diasRestantes = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                    const horasRestantes = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutosRestantes = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                    const segundosRestantes = Math.floor((diferencia % (1000 * 60)) / 1000);

                    // Formatear a dos dígitos
                    this.dias = String(diasRestantes).padStart(2, '0');
                    this.horas = String(horasRestantes).padStart(2, '0');
                    this.minutos = String(minutosRestantes).padStart(2, '0');
                    this.segundos = String(segundosRestantes).padStart(2, '0');
                }, 1000);
            }
        }
    }
</script>
@endpush
