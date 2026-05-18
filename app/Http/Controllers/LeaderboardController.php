<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Item;

class LeaderboardController extends Controller
{
    // Carga los 50 mejores puntajes aprobados filtrados por personaje.
    public function mostrarTablaDeClasificacion(Request $request)
    {
        $characterId = $request->get('character', 'all');
        $scoreMin = $request->filled('score_min') ? (int) str_replace('.', '', $request->score_min) : null;
        $scoreMax = $request->filled('score_max') ? (int) str_replace('.', '', $request->score_max) : null;

        // Subconsulta con ROW_NUMBER() para limitar a 3 puntuaciones por usuario
        $rankedScores = \Illuminate\Support\Facades\DB::table('scores')
            ->select('*', \Illuminate\Support\Facades\DB::raw('ROW_NUMBER() OVER(PARTITION BY user_id ORDER BY points DESC) as rn'))
            ->where('status', 'approved');

        if ($characterId !== 'all') {
            $rankedScores->where('character_id', $characterId);
        }

        if ($scoreMin !== null && $scoreMin !== '') {
            $rankedScores->where('points', '>=', $scoreMin);
        }

        if ($scoreMax !== null && $scoreMax !== '') {
            $rankedScores->where('points', '<=', $scoreMax);
        }

        // Envolvemos la subconsulta para filtrar donde el rn <= 3
        $topScoresQuery = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw("({$rankedScores->toSql()}) as ranked"))
            ->mergeBindings($rankedScores)
            ->where('rn', '<=', 3);

        // Extraemos los IDs
        $topScoreIds = $topScoresQuery->pluck('id');

        // Utilizamos Eloquent para traer los modelos completos y sus relaciones
        $scores = Score::with(['user', 'character', 'build'])
            ->whereIn('id', $topScoreIds)
            ->orderBy('points', 'desc')
            ->take(50)
            ->get();

        $characters = Item::where('type', 'personaje')->get();

        return view('leaderboard', compact('scores', 'characters', 'characterId'));
    }

    // Registra una nueva puntuación si supera el récord anterior del usuario.
    public function guardarNuevaPuntuacion(Request $request)
    {
        $request->validate([
            'character_id' => 'required|string|exists:items,id',
            'points' => 'required|integer|min:0',
            'time' => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'build_id' => 'nullable|exists:builds,id'
        ]);

        $userId = auth()->id();
        $characterId = $request->character_id;
        $newPoints = $request->points;

        $existingScore = Score::where('user_id', $userId)
            ->where('character_id', $characterId)
            ->first();

        if ($existingScore) {
            if ($newPoints > $existingScore->points) {
                $existingScore->update([
                    'points' => $newPoints,
                    'time' => $request->time,
                    'build_id' => $request->build_id,
                    'status' => 'pending' // Anti-cheats: Pasa a pendiente
                ]);
                return back()->with('success', '¡Puntuación enviada! Tu récord está en proceso de revisión por el equipo de administración.');
            } else {
                return back()->with('error', '¡Buen intento! Pero tu récord actual sigue siendo el mejor');
            }
        }

        Score::create([
            'user_id' => $userId,
            'character_id' => $characterId,
            'build_id' => $request->build_id,
            'points' => $newPoints,
            'time' => $request->time,
            'status' => 'pending', // Anti-cheats: Pasa a pendiente
        ]);

        return back()->with('success', '¡Puntuación enviada! Tu récord está en proceso de revisión por el equipo de administración.');
    }
}
