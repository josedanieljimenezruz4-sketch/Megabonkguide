<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameDataController extends Controller
{
    public function tierlist()
    {
        return view('tierlist');
    }

    public function meta()
    {
        return view('meta');
    }

    public function builds(Request $request)
    {
        $builds = [
            [
                'title' => 'Build Definitiva: Crítico Infinito',
                'rating' => 5,
                'author' => 'ProBonker99',
                'character' => 'La Maestra del Bonk',
                'weapon' => 'Hacha Púrpura',
                'description' => 'Maximizando el Tomo de Velocidad, esta build logra una tasa de crítico del 100% y un Bonk perpetuo. Ideal para el nivel Bonk +10.',
                'tags' => ['DPS', 'Crítico']
            ],
            [
                'title' => 'Build Tanque: El Muro de Hueso',
                'rating' => 4,
                'author' => 'TankMaster',
                'character' => 'El Berserker',
                'weapon' => 'Mazo de Guerra',
                'description' => 'Enfocada en el Item Único "Armadura del Olvido" para generar escudos pasivos. Extremadamente difícil de derribar.',
                'tags' => ['Tanque', 'Survival']
            ],
            [
                'title' => 'Ilusionista de Control',
                'rating' => 5,
                'author' => 'MageSupreme',
                'character' => 'La Ilusionista',
                'weapon' => 'Bastón de Cobre',
                'description' => 'Control de masas absoluto con clones que explotan. Perfecta para limpiar oleadas grandes sin recibir daño.',
                'tags' => ['Control', 'AoE']
            ],
            [
                'title' => 'Speedrunner Bonk',
                'rating' => 3,
                'author' => 'FastBoi',
                'character' => 'La Maestra del Bonk',
                'weapon' => 'Dagas de Viento',
                'description' => 'Todo a velocidad de movimiento y ataque. Limpia mapas de bajo nivel en tiempo récord.',
                'tags' => ['Speedrun', 'DPS']
            ]
        ];

        if ($request->ajax() || $request->wantsJson()) {
            $query = $request->get('search');
            $character = $request->get('character');
            $weapon = $request->get('weapon');

            $filtered = array_filter($builds, function ($build) use ($query, $character, $weapon) {
                if ($query && stripos($build['title'], $query) === false) {
                    return false;
                }
                if ($character && stripos($build['character'], $character) === false) {
                    return false;
                }
                if ($weapon && stripos($build['weapon'], $weapon) === false) {
                    return false;
                }
                return true;
            });

            return response()->json(array_values($filtered));
        }

        return view('buscador_builds');
    }

    public function leaderboard()
    {
        return view('leaderboard');
    }
}
