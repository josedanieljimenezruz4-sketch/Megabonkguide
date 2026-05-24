@extends('layouts.admin')

@section('title', 'Moderación | Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-white flex items-center gap-3 mb-2">🛡️ Moderación de Comunidad</h1>
        <p class="text-gray-400">Administra, edita o elimina contenido generado por los usuarios (Builds y Posts).</p>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border-l-4 border-green-500 p-4 mb-8 text-white rounded shadow-[0_0_15px_rgba(34,197,94,0.2)]">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-8">
        
        <!-- BUILDS -->
        <div class="glass-card p-6 rounded-2xl mb-8 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <h2 class="text-xl text-cyan-400 font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">⚒️ Moderación de Builds</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-black/60 text-xs uppercase tracking-wider text-cyan-400">
                        <tr>
                            <th class="p-4 rounded-tl-lg font-semibold">Autor</th>
                            <th class="p-4 font-semibold">Título</th>
                            <th class="p-4 font-semibold">Personaje</th>
                            <th class="p-4 font-semibold">Valoración</th>
                            <th class="p-4 text-center rounded-tr-lg font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-300">
                        @forelse($builds as $build)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-white font-medium">{{ $build->user->username ?? $build->user->name ?? 'Anónimo' }}</td>
                                <td class="p-4 font-semibold">{{ \Illuminate\Support\Str::limit($build->name, 30) }}</td>
                                <td class="p-4 text-gray-400">{{ $build->character->name ?? 'N/A' }}</td>
                                <td class="p-4"><strong class="text-yellow-500">{{ $build->rating }} ⭐</strong></td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('builds.show', $build->id) }}" target="_blank" class="bg-gray-800/50 hover:bg-gray-700 text-gray-300 hover:text-white px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all text-xs font-medium">
                                            👁️ Ver
                                        </a>
                                        <a href="{{ route('admin.moderation.builds.edit', $build->id) }}" class="border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500 hover:text-black hover:shadow-[0_0_15px_rgba(6,182,212,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.moderation.builds.destroy', $build->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta Build?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-500 italic">No hay builds registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center">{{ $builds->links() }}</div>
        </div>

        <!-- COMMUNITY POSTS -->
        <div class="glass-card p-6 rounded-2xl mb-8 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <h2 class="text-xl text-green-400 font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">💬 Moderación de Posts de Comunidad</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-black/60 text-xs uppercase tracking-wider text-green-400">
                        <tr>
                            <th class="p-4 rounded-tl-lg font-semibold">Autor</th>
                            <th class="p-4 font-semibold">Título</th>
                            <th class="p-4 font-semibold">Categoría</th>
                            <th class="p-4 font-semibold">Fecha</th>
                            <th class="p-4 text-center rounded-tr-lg font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-300">
                        @forelse($posts as $post)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-white font-medium">{{ $post->user->username ?? 'Anónimo' }}</td>
                                <td class="p-4 font-semibold">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</td>
                                <td class="p-4"><span class="bg-green-500/10 text-green-400 px-3 py-1 rounded-full text-xs uppercase border border-green-500/20">{{ $post->category ?? 'General' }}</span></td>
                                <td class="p-4 text-gray-400">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('comunity.show', $post->id) }}" target="_blank" class="bg-gray-800/50 hover:bg-gray-700 text-gray-300 hover:text-white px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all text-xs font-medium">
                                            👁️ Ver
                                        </a>
                                        <form action="{{ route('admin.moderation.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este post y todos sus comentarios?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-500 italic">No hay posts de comunidad.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center">{{ $posts->links() }}</div>
        </div>

    </div>
</div>
@endsection
