@extends('layouts.app')

@section('title', 'Novedades | MEGABONK GUIDE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/novedades.css') }}">
@endpush

@section('content')
    <main class="main-content-news">

        <h1 class="page-title">📣 Últimas Novedades de MEGABONK</h1>

        <p class="intro-text-news">
            Mantente al día con los últimos parches, eventos especiales y anuncios oficiales. ¡Las novedades se listan
            de forma cronológica!
        </p>

        <section class="news-timeline">

            @foreach ($updates as $update)
                <div class="timeline-item">
                    <div class="date-tag">{{ strtoupper(\Carbon\Carbon::parse($update->published_at)->translatedFormat('j M Y')) }}</div>
                    <div class="news-card {{ $loop->first ? 'current' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <h2>{{ $update->title }}</h2>
                            @if ($update->source === 'steam')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 496 512" fill="currentColor" style="opacity: 0.7;">
                                    <!-- FontAwesome Steam Logo SVG -->
                                    <path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1l-16.3-22.5C186 461 219.7 464 248 464c114.9 0 208-93.1 208-208S362.9 48 248 48 40 141.1 40 256c0 18.2 2.4 35.8 6.8 52.6l-20.7 29.1C8.6 312.3 0 285.2 0 256 0 119 111 8 248 8s248 111 248 248zM140.7 348.6l-37 51c-8.9 12.3-19.1 23.3-30.2 33 24 21.8 55.4 35.5 89.8 35.5 13.9 0 27.2-2.1 39.8-6l-18.7-25.9c-16.7-16-36.8-40.4-43.7-87.6zM248 256c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zm0-64c-48.6 0-88 39.4-88 88s39.4 88 88 88 88-39.4 88-88-39.4-88-88-88zm0 144c-30.9 0-56-25.1-56-56s25.1-56 56-56 56 25.1 56 56-25.1 56-56 56z"/>
                                </svg>
                            @endif
                        </div>
                        
                        @if ($update->type === 'patch')
                            <span class="news-type tag-patch">PARCHE</span>
                        @else
                            <span class="news-type tag-event">EVENTO/NOTICIA</span>
                        @endif
                        
                        <p>{{ $update->content }}</p>

                        @if ($update->url)
                            <a href="{{ $update->url }}" target="_blank" rel="noopener noreferrer" class="read-more">Ver más en Steam →</a>
                        @endif
                    </div>
                </div>
            @endforeach

            @if ($updates->isEmpty())
                <p class="text-center">No hay novedades por el momento.</p>
            @endif

        </section>

        <div class="archive-cta">
            <a href="#" class="btn-archive">Ver Archivo Histórico de Novedades</a>
        </div>

    </main>
@endsection
