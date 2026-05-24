@extends('layouts.admin')

@section('title', 'Catálogo de Unlocks | Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white flex items-center gap-3 mb-2">📦 Catálogo de Unlocks</h1>
        <p class="text-gray-400">Gestiona todos los elementos del juego: Personajes, Armas, Tomos e Ítems.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border-l-4 border-green-500 p-4 mb-8 text-white rounded shadow-[0_0_15px_rgba(34,197,94,0.2)]">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pestañas de filtro --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('admin.catalogo.index') }}" data-ajax-tab
           class="px-5 py-2.5 rounded-full font-bold text-sm transition-all duration-300 {{ !$tipoActual ? 'bg-white text-black shadow-[0_0_15px_rgba(255,255,255,0.3)]' : 'bg-white/10 text-gray-300 hover:bg-white/20 border border-white/10' }}">
            📋 Todos
        </a>
        @php
            $tipos = [
                'personaje' => ['emoji' => '🧙', 'label' => 'Personajes', 'color' => '#B965F0'],
                'arma' => ['emoji' => '⚔️', 'label' => 'Armas', 'color' => '#ff416c'],
                'tomo' => ['emoji' => '📖', 'label' => 'Tomos', 'color' => '#ffcf00'],
                'item' => ['emoji' => '💎', 'label' => 'Ítems', 'color' => '#41E8EF'],
            ];
        @endphp
        @foreach($tipos as $tipo => $info)
            <a href="{{ route('admin.catalogo.index', ['type' => $tipo]) }}" data-ajax-tab
               class="px-5 py-2.5 rounded-full font-bold text-sm transition-all duration-300 {{ $tipoActual === $tipo ? 'text-black shadow-[0_0_15px_rgba(255,255,255,0.2)]' : 'bg-white/10 text-gray-300 hover:bg-white/20 border border-white/10' }}"
               style="{{ $tipoActual === $tipo ? 'background:' . $info['color'] . ';' : '' }}">
                {{ $info['emoji'] }} {{ $info['label'] }}
            </a>
        @endforeach

        {{-- Botón crear --}}
        <a href="{{ route('admin.items.create') }}" class="ml-auto px-5 py-2.5 rounded-full font-bold text-sm bg-green-500 text-white hover:bg-green-400 transition-all shadow-[0_0_15px_rgba(34,197,94,0.3)] inline-flex items-center gap-2">
            ➕ Añadir Nuevo
        </a>
    </div>

    {{-- Tabla del catálogo --}}
    <div class="glass-card p-6 rounded-2xl bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/60 text-xs uppercase tracking-wider text-cyan-400">
                    <tr>
                        <th class="p-4 rounded-tl-lg font-semibold w-16">Img</th>
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Nombre</th>
                        <th class="p-4 font-semibold">Tipo</th>
                        <th class="p-4 font-semibold">Descripción</th>
                        <th class="p-4 text-center rounded-tr-lg font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @forelse($items as $item)
                        @php
                            $imgSrc = asset($item->image_url);
                        @endphp
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                <img src="{{ $imgSrc }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                     class="w-10 h-10 object-contain rounded bg-black/50">
                            </td>
                            <td class="p-4 text-gray-400 font-mono text-xs">{{ $item->id }}</td>
                            <td class="p-4 text-white font-semibold">{{ $item->name }}</td>
                            <td class="p-4">
                                @php
                                    $colores = ['personaje' => 'purple', 'arma' => 'red', 'tomo' => 'yellow', 'item' => 'cyan'];
                                    $c = $colores[$item->type] ?? 'gray';
                                @endphp
                                <span class="bg-{{ $c }}-500/10 text-{{ $c }}-400 px-3 py-1 rounded-full text-xs uppercase border border-{{ $c }}-500/20 font-bold">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-400 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($item->description, 50) }}</td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.catalogo.edit', $item->id) }}"
                                       class="border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500 hover:text-black hover:shadow-[0_0_15px_rgba(6,182,212,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-1 transition-all font-semibold text-xs">
                                        ✏️ Editar
                                    </a>
                                    <form action="{{ route('admin.catalogo.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar el ítem «{{ $item->name }}» permanentemente?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-1 transition-all font-semibold text-xs">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-500 italic">No hay ítems registrados{{ $tipoActual ? ' en esta categoría' : '' }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">{{ $items->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const CONTENT_SELECTOR = '.container.mx-auto';
    const TAB_SELECTOR = '[data-ajax-tab]';

    function attachTabListeners() {
        document.querySelectorAll(TAB_SELECTOR).forEach(function(tab) {
            tab.addEventListener('click', handleTabClick);
        });
    }

    function handleTabClick(e) {
        e.preventDefault();
        const url = this.href;
        const container = document.querySelector(CONTENT_SELECTOR);
        if (!container) return;

        // Fade out
        container.style.transition = 'opacity 0.2s ease';
        container.style.opacity = '0.4';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const nuevoContenido = doc.querySelector(CONTENT_SELECTOR);

                if (nuevoContenido) {
                    container.innerHTML = nuevoContenido.innerHTML;
                }

                // Actualizar URL sin recargar
                history.pushState(null, '', url);

                // Re-attach listeners al nuevo contenido
                attachTabListeners();

                // Fade in
                container.style.opacity = '1';
            })
            .catch(function() {
                // Si falla el AJAX, navegar normalmente
                window.location.href = url;
            });
    }

    // Soporte para botón atrás del navegador
    window.addEventListener('popstate', function() {
        window.location.reload();
    });

    // Inicializar
    attachTabListeners();
})();
</script>
@endpush
