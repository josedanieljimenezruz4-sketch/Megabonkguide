<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function tierlistManager()
    {
        $allItems = \App\Models\Item::orderBy('name', 'asc')->get();
        $itemsByRank = $allItems->whereNotNull('rank')->groupBy('rank');
        $pendingItems = $allItems->whereNull('rank');

        return view('admin.tierlist-manager', compact('itemsByRank', 'pendingItems'));
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalUnlocks = DB::table('user_unlocks')->count();
        $totalItems = \App\Models\Item::count();
        $totalAdmins = User::where('is_admin', true)->count();

        // Obtenemos los últimos 10 usuarios para mostrarlos en la tabla
        $latestUsers = User::latest()->take(10)->get();

        return view('admin', compact('totalUsers', 'totalAdmins', 'totalUnlocks', 'totalItems', 'latestUsers'));
    }

    public function votes()
    {
        $items = \App\Models\Item::orderBy('votes', 'desc')->paginate(15);
        return view('admin-votes', compact('items'));
    }

    public function resetAllVotes()
    {
        DB::table('item_user_votes')->truncate();
        \App\Models\Item::query()->update(['votes' => 0]);
        
        return redirect()->route('admin.votes.index')->with('success', 'Todos los votos han sido reseteados.');
    }

    public function resetItemVotes($id)
    {
        $item = \App\Models\Item::findOrFail($id);
        
        // Remove pivot entries for that element
        DB::table('item_user_votes')->where('item_id', $id)->delete();
        $item->update(['votes' => 0]);

        return redirect()->route('admin.votes.index')->with('success', 'Los votos han sido reseteados para: ' . $item->name);
    }

    public function communityTierLists()
    {
        $tierLists = \App\Models\TierList::with('user')->latest()->paginate(15);
        return view('admin.community_tierlists', compact('tierLists'));
    }

    public function leaderboard()
    {
        $pendingScores = \App\Models\Score::with(['user', 'character', 'build'])
            ->where('status', 'pending')
            ->oldest()
            ->get();
            
        $approvedScores = \App\Models\Score::with(['user', 'character', 'build'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(15);
            
        return view('admin.leaderboard', compact('pendingScores', 'approvedScores'));
    }

    public function resetGlobalLeaderboard()
    {
        \App\Models\Score::where('status', 'approved')->delete();
        return redirect()->back()->with('success', 'LEADERBOARD GLOBAL REINICIADO. Todas las puntuaciones han sido archivadas de forma segura.');
    }

    public function resetUserScore($id)
    {
        $score = \App\Models\Score::findOrFail($id);
        $score->delete();
        return redirect()->back()->with('success', 'Puntuación individual reseteada correctamente.');
    }

    public function approveScore($id)
    {
        $score = \App\Models\Score::findOrFail($id);
        
        // Al aprobar un score, buscamos si este usuario tenía otro score aprobado para la misma categoría (dificultad + personaje)
        // y lo borramos o rechazamos, porque solo puede haber 1 por categoría.
        \App\Models\Score::where('user_id', $score->user_id)
            ->where('character_id', $score->character_id)
            ->where('difficulty', $score->difficulty)
            ->where('status', 'approved')
            ->where('id', '!=', $score->id)
            ->delete();

        $score->update(['status' => 'approved']);

        return back()->with('success', 'Puntuación aprobada correctamente. Ha reemplazado los récords anteriores del usuario en esta categoría si existían.');
    }

    public function rejectScore($id)
    {
        $score = \App\Models\Score::findOrFail($id);
        $score->update(['status' => 'rejected']);

        return back()->with('success', 'Puntuación rechazada.');
    }
}
