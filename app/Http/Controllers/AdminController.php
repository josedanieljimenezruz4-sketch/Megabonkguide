<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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
}
