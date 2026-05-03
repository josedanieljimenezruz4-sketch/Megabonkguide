<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\Item;
use App\Models\Update;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // 1. Top Build: Build con más votos
        $topBuild = Build::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->with(['user', 'character'])
            ->first();

        // 2. Wiki Short: Personaje o ítem aleatorio
        $randomItem = Item::inRandomOrder()->first();

        // 3. Steam News: Último parche de la API (guardado en DB)
        $latestUpdate = Update::where('source', 'steam')->latest('published_at')->first();

        // 4. Estadísticas
        $buildsCount = Build::count();
        $usersCount = User::count();
        $newsCount = Update::count();

        return view('welcome', compact(
            'topBuild',
            'randomItem',
            'latestUpdate',
            'buildsCount',
            'usersCount',
            'newsCount'
        ));
    }
}
