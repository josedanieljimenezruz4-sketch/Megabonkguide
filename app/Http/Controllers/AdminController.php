<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\TierList;
use App\Models\Score;
use App\Models\Build;
use App\Models\CommunityPost;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Muestra la vista de gestión de la Tier List Oficial (ítems y rangos).
     */
    public function gestionarTierlist()
    {
        $todosLosElementos = Item::orderBy('name', 'asc')->get();
        $elementosPorRango = $todosLosElementos->whereNotNull('rank')->groupBy('rank');
        $elementosPendientes = $todosLosElementos->whereNull('rank');

        return view('admin.tierlist-manager', [
            'elementosPorRango' => $elementosPorRango,
            'elementosPendientes' => $elementosPendientes
        ]);
    }

    /**
     * Muestra el panel de control (Dashboard) principal de administración.
     */
    public function mostrarPanelAdministracion()
    {
        $totalUsuarios = User::count();
        $totalDesbloqueos = DB::table('user_unlocks')->count();
        $totalElementos = Item::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalPosts = CommunityPost::count();
        $totalBuilds = Build::count();

        // Obtenemos los últimos 10 usuarios para mostrarlos en la tabla
        $ultimosUsuarios = User::latest()->take(10)->get();

        return view('admin', [
            'totalUsuarios' => $totalUsuarios,
            'totalAdmins' => $totalAdmins,
            'totalDesbloqueos' => $totalDesbloqueos,
            'totalElementos' => $totalElementos,
            'totalPosts' => $totalPosts,
            'totalBuilds' => $totalBuilds,
            'ultimosUsuarios' => $ultimosUsuarios
        ]);
    }



    /**
     * Muestra el gestor de Tier Lists de la comunidad (Modo antiguo).
     */
    public function gestionarTierListsComunidad()
    {
        $tierLists = TierList::with('user')->latest()->paginate(15);
        return view('admin.community_tierlists', ['tierLists' => $tierLists]);
    }

    /**
     * Muestra el gestor del Leaderboard (puntuaciones pendientes y aprobadas).
     */
    public function gestionarLeaderboard()
    {
        $puntuacionesPendientes = Score::with(['user', 'character', 'build'])
            ->where('status', 'pending')
            ->oldest()
            ->get();
            
        $puntuacionesAprobadas = Score::with(['user', 'character', 'build'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(15);
            
        return view('admin.leaderboard', [
            'pendingScores' => $puntuacionesPendientes,
            'approvedScores' => $puntuacionesAprobadas
        ]);
    }

    /**
     * Limpia las puntuaciones rechazadas (spam) del leaderboard.
     * Sustituye la acción destructiva de "Reiniciar Global" por una purga selectiva.
     */
    public function limpiarRechazadas()
    {
        $totalEliminadas = Score::where('status', 'rejected')->count();
        Score::where('status', 'rejected')->delete();
        return redirect()->back()->with('success', "Se han purgado {$totalEliminadas} puntuaciones rechazadas (spam) del sistema.");
    }

    /**
     * Reinicia el leaderboard global: elimina TODAS las puntuaciones del sistema.
     * Acción destructiva para reinicio de temporadas.
     */
    public function resetGlobalLeaderboard()
    {
        $totalEliminadas = Score::count();
        Score::query()->delete();
        return redirect()->back()->with('success', "⚠️ LEADERBOARD REINICIADO: Se han eliminado {$totalEliminadas} puntuaciones del sistema. Nueva temporada iniciada.");
    }

    /**
     * Purga registros huérfanos de user_unlocks cuyo item_id ya no existe en items.
     * Herramienta de mantenimiento para mantener la integridad de la base de datos.
     */
    public function purgarHuerfanos()
    {
        $totalPurgados = DB::table('user_unlocks')
            ->whereNotIn('item_id', function ($query) {
                $query->select('id')->from('items');
            })
            ->count();

        DB::table('user_unlocks')
            ->whereNotIn('item_id', function ($query) {
                $query->select('id')->from('items');
            })
            ->delete();

        return redirect()->back()->with('success', "Mantenimiento completado: se han purgado {$totalPurgados} registros huérfanos de user_unlocks.");
    }

    /**
     * Elimina permanentemente una puntuación individual.
     */
    public function reiniciarPuntuacionUsuario($id)
    {
        $puntuacion = Score::findOrFail($id);
        $puntuacion->delete();
        return redirect()->back()->with('success', 'Puntuación individual reseteada correctamente.');
    }

    /**
     * Aprueba una puntuación pendiente, invalidando todos los records anteriores del usuario (un solo récord global).
     */
    public function aprobarPuntuacion($id)
    {
        $puntuacion = Score::findOrFail($id);
        
        // Al aprobar un score, buscamos si este usuario tenía otro score aprobado
        // (sin importar el personaje) y lo borramos, porque solo puede tener un récord activo globalmente.
        Score::where('user_id', $puntuacion->user_id)
            ->where('status', 'approved')
            ->where('id', '!=', $puntuacion->id)
            ->delete();

        $puntuacion->update(['status' => 'approved']);

        return back()->with('success', 'Puntuación aprobada correctamente. Ha reemplazado el récord anterior del usuario.');
    }

    /**
     * Rechaza una puntuación pendiente.
     */
    public function rechazarPuntuacion($id)
    {
        $puntuacion = Score::findOrFail($id);
        $puntuacion->update(['status' => 'rejected']);

        return back()->with('success', 'Puntuación rechazada.');
    }

    /**
     * Muestra las sugerencias de tier agrupadas por ítem.
     */
    public function gestionarTierSuggestions()
    {
        // Obtener sugerencias pendientes agrupadas por ítem
        $sugerenciasRaw = \App\Models\TierSuggestion::with(['user', 'item'])
            ->where('status', 'pending')
            ->get();

        // Agrupar por item_id
        $agrupadas = $sugerenciasRaw->groupBy('item_id')->map(function ($grupo) {
            $item = $grupo->first()->item;
            $conteoRangos = $grupo->groupBy('suggested_tier')->map->count()->sortDesc();
            $rangoMayoritario = $conteoRangos->keys()->first();
            $totalVotos = $grupo->count();
            $usuarios = $grupo->map(fn($s) => [
                'id' => $s->id,
                'username' => $s->user->username ?? 'Anónimo',
                'tier' => $s->suggested_tier,
                'fecha' => $s->created_at->format('d/m H:i'),
            ]);

            return (object) [
                'item' => $item,
                'item_id' => $grupo->first()->item_id,
                'conteo_rangos' => $conteoRangos,
                'rango_mayoritario' => $rangoMayoritario,
                'total_votos' => $totalVotos,
                'usuarios' => $usuarios,
            ];
        })->sortByDesc('total_votos');

        return view('admin.tier_suggestions', compact('agrupadas'));
    }

    /**
     * Aprueba el rango mayoritario de un ítem y limpia todas sus sugerencias pendientes.
     */
    public function aprobarMayoria(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        // Si el admin eligió un rango manualmente, usarlo. Si no, calcular la mayoría.
        $rangoFinal = $request->input('rank');

        if (!$rangoFinal || !in_array($rangoFinal, ['S','A','B','C','D','E','F'])) {
            $rangoGanador = DB::table('tier_suggestions')
                ->select('suggested_tier', DB::raw('count(*) as total'))
                ->where('item_id', $itemId)
                ->where('status', 'pending')
                ->groupBy('suggested_tier')
                ->orderByDesc('total')
                ->first();

            if (!$rangoGanador) {
                return back()->with('success', 'No hay sugerencias pendientes para este ítem.');
            }
            $rangoFinal = $rangoGanador->suggested_tier;
        }

        DB::transaction(function () use ($item, $rangoFinal, $itemId) {
            $item->update(['rank' => $rangoFinal]);

            \App\Models\TierSuggestion::where('item_id', $itemId)
                ->where('status', 'pending')
                ->update(['status' => 'approved']);
        });

        return back()->with('success', '✅ ' . $item->name . ' asignado al rango ' . $rangoFinal . '. Sugerencias limpiadas.');
    }

    /**
     * Aprueba una sugerencia de tier, actualizando el ítem y rechazando otras pendientes.
     */
    public function aprobarTierSuggestion($id)
    {
        $sugerencia = \App\Models\TierSuggestion::findOrFail($id);
        
        // Actualizar el rango del ítem
        $item = $sugerencia->item;
        $item->update(['rank' => $sugerencia->suggested_tier]);
        
        // Marcar esta sugerencia como aprobada
        $sugerencia->update(['status' => 'approved']);
        
        // Marcar el resto de sugerencias pendientes de este ítem como rechazadas (procesadas)
        \App\Models\TierSuggestion::where('item_id', $sugerencia->item_id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);
            
        return back()->with('success', 'Sugerencia aprobada. El ítem ' . $item->name . ' ahora es rango ' . $item->rank . '. Se han marcado las demás sugerencias como procesadas.');
    }

    /**
     * Rechaza una sugerencia de tier.
     */
    public function rechazarTierSuggestion($id)
    {
        $sugerencia = \App\Models\TierSuggestion::findOrFail($id);
        $sugerencia->update(['status' => 'rejected']);
        
        return back()->with('success', 'Sugerencia de tier rechazada.');
    }

    /**
     * Reinicia la Tier List oficial: pone todos los rangos a null usando una transacción.
     * Si algo falla a mitad del proceso, la tabla no se queda a medias (rollback automático).
     */
    public function resetMeta()
    {
        DB::transaction(function () {
            // Poner todos los rangos a null
            Item::whereNotNull('rank')->update(['rank' => null]);

            // Marcar todas las sugerencias pendientes como procesadas
            \App\Models\TierSuggestion::where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return back()->with('success', '⚠️ META REINICIADA. Todos los ítems han perdido su rango. Las sugerencias pendientes han sido archivadas.');
    }

    /**
     * Banea/elimina una sugerencia de tier considerada troll o spam.
     */
    public function banearTierSuggestion($id)
    {
        $sugerencia = \App\Models\TierSuggestion::findOrFail($id);
        $nombreUsuario = $sugerencia->user->username ?? 'Desconocido';
        $sugerencia->delete();

        return back()->with('success', 'Sugerencia de "' . $nombreUsuario . '" eliminada permanentemente (ban de voto).');
    }
}
