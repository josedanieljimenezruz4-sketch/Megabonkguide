@extends('layouts.admin')

@section('title', 'Leaderboard Moderation | Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">🏆 Moderación de Leaderboard</h1>
            <p class="text-gray-400">Aprueba, rechaza y resetea las puntuaciones enviadas por los usuarios.</p>
        </div>
        <div>
            <button onclick="openGlobalResetModal()" class="bg-red-600/20 border-2 border-red-500 text-red-500 hover:bg-red-600 hover:text-white hover:shadow-[0_0_30px_rgba(220,38,38,0.8)] px-6 py-3 rounded-xl font-bold uppercase tracking-widest transition-all inline-flex items-center gap-3">
                ⚠️ REINICIAR LEADERBOARD GLOBAL
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border-l-4 border-green-500 p-4 mb-8 text-white rounded shadow-[0_0_15px_rgba(34,197,94,0.2)]">
            {{ session('success') }}
        </div>
    @endif

    <!-- Puntuaciones Pendientes -->
    <div class="glass-card p-6 rounded-2xl mb-8 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <h2 class="text-xl text-yellow-400 font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">🏅 Puntuaciones Pendientes</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/60 text-xs uppercase tracking-wider text-cyan-400">
                    <tr>
                        <th class="p-4 rounded-tl-lg font-semibold">Usuario</th>
                        <th class="p-4 font-semibold">Personaje</th>
                        <th class="p-4 font-semibold">Dificultad</th>
                        <th class="p-4 font-semibold">Puntuación</th>
                        <th class="p-4 font-semibold">Tiempo</th>
                        <th class="p-4 font-semibold">Prueba</th>
                        <th class="p-4 font-semibold">Build</th>
                        <th class="p-4 text-center rounded-tr-lg font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @forelse($pendingScores as $score)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                            <td class="p-4 text-white font-medium">{{ $score->user->name }}</td>
                            <td class="p-4 text-gray-400">{{ $score->character->name ?? 'N/A' }}</td>
                            <td class="p-4"><span class="px-2 py-1 bg-white/10 rounded text-xs">{{ strtoupper($score->difficulty) }}</span></td>
                            <td class="p-4"><strong class="text-yellow-500 text-lg group-hover:text-yellow-400 transition-colors">{{ number_format($score->points) }}</strong></td>
                            <td class="p-4 text-gray-400">{{ $score->time }}</td>
                            <td class="p-4">
                                <a href="{{ $score->proof_url }}" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline decoration-cyan-500/30 underline-offset-4">Ver Prueba ↗</a>
                            </td>
                            <td class="p-4">
                                @if($score->build)
                                    <a href="{{ route('builds.show', $score->build_id) }}" target="_blank" class="bg-gray-800/50 hover:bg-gray-700 text-gray-300 hover:text-white px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all text-xs">👁️ Ver Build</a>
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.leaderboard.approve', $score->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500 hover:text-black hover:shadow-[0_0_15px_rgba(6,182,212,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">✓ Aprobar</button>
                                    </form>
                                    <form action="{{ route('admin.leaderboard.reject', $score->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres rechazar este récord?');">
                                        @csrf
                                        <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-3 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">🗑️ Rechazar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-gray-500 italic">No hay puntuaciones pendientes de moderación en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Puntuaciones Aprobadas -->
    <div class="glass-card p-6 rounded-2xl mb-8 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <h2 class="text-xl text-cyan-400 font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">🏆 Puntuaciones Aprobadas (Activas)</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/60 text-xs uppercase tracking-wider text-cyan-400">
                    <tr>
                        <th class="p-4 rounded-tl-lg font-semibold">Usuario</th>
                        <th class="p-4 font-semibold">Personaje</th>
                        <th class="p-4 font-semibold">Dificultad</th>
                        <th class="p-4 font-semibold">Puntuación</th>
                        <th class="p-4 font-semibold">Tiempo</th>
                        <th class="p-4 text-center rounded-tr-lg font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @forelse($approvedScores as $score)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                            <td class="p-4 text-white font-medium">{{ $score->user->username ?? $score->user->name }}</td>
                            <td class="p-4 text-gray-400">{{ $score->character->name ?? 'N/A' }}</td>
                            <td class="p-4"><span class="px-2 py-1 bg-white/10 rounded text-xs">{{ strtoupper($score->difficulty) }}</span></td>
                            <td class="p-4"><strong class="text-cyan-400 text-lg group-hover:text-cyan-300 transition-colors drop-shadow-[0_0_5px_rgba(6,182,212,0.3)]">{{ number_format($score->points) }}</strong></td>
                            <td class="p-4 text-gray-400">{{ $score->time }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.leaderboard.resetUser', $score->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este récord del jugador?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] px-4 py-1.5 rounded-full inline-flex items-center gap-2 transition-all font-semibold text-xs">
                                        🔄 Reiniciar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-500 italic">No hay puntuaciones aprobadas todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $approvedScores->links() }}
        </div>
    </div>
</div>

<!-- Global Reset Modal -->
<div id="globalResetModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: #111; padding: 40px; border-radius: 12px; width: 90%; max-width: 500px; border: 2px solid #ff0000; box-shadow: 0 0 30px rgba(255, 0, 0, 0.4); text-align: center;">
        <h1 style="color: #ff0000; margin-top: 0; font-size: 2.5rem;">¡CUIDADO!</h1>
        <p style="color: white; font-size: 1.1rem; line-height: 1.5;">Estás a punto de <strong>reiniciar por completo</strong> la clasificación global. Todos los récords actuales serán eliminados de la vista pública (aunque se guardará un log de seguridad mediante soft deletes).</p>
        <p style="color: #ffaa00; font-weight: bold; margin: 20px 0;">Esta acción obligará a todos los jugadores a volver a subir sus puntuaciones para el nuevo periodo.</p>
        
        <form action="{{ route('admin.leaderboard.resetGlobal') }}" method="POST" id="globalResetForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 30px;">
                <label style="color: white; font-size: 0.9rem;">Escribe "REINICIAR" para confirmar:</label>
                <input type="text" id="confirmText" onkeyup="checkResetText()" style="padding: 10px; text-align: center; font-weight: bold; font-size: 1.2rem; background: #222; border: 1px solid #444; color: white;" autocomplete="off">
                
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="closeGlobalResetModal()" style="flex: 1; padding: 15px; background: #444; color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 5px;">CANCELAR</button>
                    <button type="button" id="confirmResetBtn" disabled onclick="finalConfirm()" style="flex: 1; padding: 15px; background: #330000; color: #888; border: 1px solid #550000; font-weight: bold; cursor: not-allowed; border-radius: 5px; transition: all 0.3s;">CONFIRMAR DESTRUCCIÓN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openGlobalResetModal() {
        document.getElementById('globalResetModal').style.display = 'flex';
        document.getElementById('confirmText').value = '';
        checkResetText();
    }

    function closeGlobalResetModal() {
        document.getElementById('globalResetModal').style.display = 'none';
    }

    function checkResetText() {
        const text = document.getElementById('confirmText').value;
        const btn = document.getElementById('confirmResetBtn');
        if (text === 'REINICIAR') {
            btn.disabled = false;
            btn.style.background = '#ff0000';
            btn.style.color = 'white';
            btn.style.cursor = 'pointer';
            btn.style.boxShadow = '0 0 15px rgba(255,0,0,0.5)';
        } else {
            btn.disabled = true;
            btn.style.background = '#330000';
            btn.style.color = '#888';
            btn.style.cursor = 'not-allowed';
            btn.style.boxShadow = 'none';
        }
    }

    function finalConfirm() {
        if (confirm('ÚLTIMO AVISO: ¿Estás absolutamente seguro de que quieres borrar todos los récords globales?')) {
            document.getElementById('globalResetForm').submit();
        }
    }
</script>
@endsection
