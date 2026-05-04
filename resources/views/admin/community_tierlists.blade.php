@extends('layouts.admin')

@section('title', 'Tier Lists de la Comunidad | MEGABONK GUIDE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white flex items-center gap-3">📋 Tier Lists de la Comunidad</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-purple-400 hover:text-purple-300 font-bold transition-colors">&larr; Volver al Panel</a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border-l-4 border-green-500 p-4 mb-8 text-white rounded shadow-[0_0_15px_rgba(34,197,94,0.2)]">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card p-6 rounded-2xl mb-8 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <h2 class="text-xl text-purple-400 font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">🛡️ Gestión de Tier Lists</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/60 text-xs uppercase tracking-wider text-purple-400">
                    <tr>
                        <th class="p-4 rounded-tl-lg font-semibold">ID</th>
                        <th class="p-4 font-semibold">Título</th>
                        <th class="p-4 font-semibold">Autor</th>
                        <th class="p-4 font-semibold">Categoría</th>
                        <th class="p-4 font-semibold">Fecha</th>
                        <th class="p-4 text-center rounded-tr-lg font-semibold">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @forelse($tierLists as $tl)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <td class="p-4 font-bold text-purple-500">#{{ $tl->id }}</td>
                        <td class="p-4">
                            <a href="{{ route('community-tierlists.show', $tl->id) }}" class="text-white font-semibold hover:text-purple-400 transition-colors" target="_blank">
                                {{ Str::limit($tl->titulo, 40) }}
                            </a>
                        </td>
                        <td class="p-4 text-gray-400">{{ $tl->user->username ?? 'Anónimo' }}</td>
                        <td class="p-4"><span class="bg-white/10 px-3 py-1 rounded-full text-xs uppercase border border-white/10">{{ $tl->categoria }}</span></td>
                        <td class="p-4 text-gray-500">{{ $tl->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('community-tierlists.show', $tl->id) }}" target="_blank" class="bg-gray-800/50 hover:bg-gray-700 text-gray-300 hover:text-white px-4 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-medium text-xs">
                                    👁️ Ver
                                </a>
                                <form action="{{ route('admin.community-tierlists.destroy', $tl->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar permanentemente la Tier List: {{ addslashes($tl->titulo) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-4 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 italic">No hay Tier Lists de la comunidad registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 flex justify-center">
            @if(method_exists($tierLists, 'links'))
                {{ $tierLists->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>
</div>
@endsection
