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
     * Muestra el panel principal de la aplicación con estadísticas y destacados.
     */
    public function mostrarInicio()
    {
        // 1. Top Build: Build con más votos de la comunidad
        $topBuild = Build::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->with(['user', 'character'])
            ->first();

        // 2. Wiki Short: Personaje o ítem aleatorio para la sección "Descubre"
        $elementoAleatorio = Item::inRandomOrder()->first();

        // 3. Steam News: Último parche obtenido de la API (guardado en BD)
        $ultimaActualizacion = Update::where('source', 'steam')->latest('published_at')->first();

        // 4. Estadísticas generales del sitio
        $totalBuilds = Build::count();
        $totalUsuarios = User::count();
        $totalNoticias = Update::count();

        return view('welcome', [
            'topBuild' => $topBuild,
            'randomItem' => $elementoAleatorio,
            'latestUpdate' => $ultimaActualizacion,
            'buildsCount' => $totalBuilds,
            'usersCount' => $totalUsuarios,
            'newsCount' => $totalNoticias,
        ]);
    }
}
