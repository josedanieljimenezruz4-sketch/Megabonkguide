<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\TierList;
use App\Models\Score;
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

        // Obtenemos los últimos 10 usuarios para mostrarlos en la tabla
        $ultimosUsuarios = User::latest()->take(10)->get();

        return view('admin', [
            'totalUsuarios' => $totalUsuarios,
            'totalAdmins' => $totalAdmins,
            'totalDesbloqueos' => $totalDesbloqueos,
            'totalElementos' => $totalElementos,
            'ultimosUsuarios' => $ultimosUsuarios
        ]);
    }

    /**
     * Muestra el gestor de votos de popularidad de los elementos.
     */
    public function gestionarVotos()
    {
        $elementos = Item::orderBy('votes', 'desc')->paginate(15);
        return view('admin-votes', ['items' => $elementos]);
    }

    /**
     * Reinicia todos los votos de popularidad a 0 de forma global.
     */
    public function reiniciarTodosLosVotos()
    {
        DB::table('item_user_votes')->truncate();
        Item::query()->update(['votes' => 0]);
        
        return redirect()->route('admin.votes.index')->with('success', 'Todos los votos han sido reseteados.');
    }

    /**
     * Reinicia los votos de popularidad de un elemento específico a 0.
     */
    public function reiniciarVotosElemento($id)
    {
        $elemento = Item::findOrFail($id);
        
        DB::table('item_user_votes')->where('item_id', $id)->delete();
        $elemento->update(['votes' => 0]);

        return redirect()->route('admin.votes.index')->with('success', 'Los votos han sido reseteados para: ' . $elemento->name);
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
     * Reinicia el Leaderboard global (elimina todas las puntuaciones aprobadas).
     */
    public function reiniciarLeaderboardGlobal()
    {
        Score::where('status', 'approved')->delete();
        return redirect()->back()->with('success', 'LEADERBOARD GLOBAL REINICIADO. Todas las puntuaciones han sido archivadas de forma segura.');
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
     * Aprueba una puntuación pendiente, invalidando records anteriores del usuario en la misma categoría.
     */
    public function aprobarPuntuacion($id)
    {
        $puntuacion = Score::findOrFail($id);
        
        // Al aprobar un score, buscamos si este usuario tenía otro score aprobado para la misma categoría (dificultad + personaje)
        // y lo borramos o rechazamos, porque solo puede haber 1 por categoría.
        Score::where('user_id', $puntuacion->user_id)
            ->where('character_id', $puntuacion->character_id)
            ->where('difficulty', $puntuacion->difficulty)
            ->where('status', 'approved')
            ->where('id', '!=', $puntuacion->id)
            ->delete();

        $puntuacion->update(['status' => 'approved']);

        return back()->with('success', 'Puntuación aprobada correctamente. Ha reemplazado los récords anteriores del usuario en esta categoría si existían.');
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
}
