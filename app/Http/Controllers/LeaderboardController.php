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
        // Sanitizar puntos: el frontend envía con separador de miles (ej: 777.778)
        $request->merge(['points' => (int) str_replace('.', '', $request->points)]);

        $request->validate([
            'character_id' => 'required|string|exists:items,id',
            'points' => 'required|integer|min:0',
            'time' => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'build_id' => 'nullable|exists:builds,id'
        ], [
            'character_id.required' => 'Debes seleccionar un personaje.',
            'character_id.exists' => 'El personaje seleccionado no existe en el sistema.',
            'points.required' => 'La puntuación es obligatoria.',
            'points.integer' => 'La puntuación debe ser un número entero válido.',
            'points.min' => 'La puntuación no puede ser negativa.',
            'time.required' => 'El tiempo es obligatorio.',
            'time.regex' => 'El formato del tiempo debe ser HH:MM:SS (ej: 01:42:15).',
            'build_id.exists' => 'La build seleccionada no existe.',
        ]);

        $userId = auth()->id();
        $characterId = $request->character_id;
        $newPoints = $request->points;

        // Buscar el récord máximo global de este usuario (sin importar el personaje) pero solo aprobadas
        $maxGlobal = Score::where('user_id', auth()->id())->where('status', 'approved')->max('points') ?? 0;

        if ($maxGlobal !== null && $request->points <= $maxGlobal) {
            return redirect()->back()->with('error_toast', '¡Buen intento! Pero ya tienes un récord global superior en el Leaderboard.');
        }

        // Si ya tiene un pending anterior para este personaje, lo actualizamos en vez de crear otro
        $pendingScore = Score::where('user_id', $userId)
            ->where('character_id', $characterId)
            ->where('status', 'pending')
            ->first();

        if ($pendingScore) {
            $pendingScore->update([
                'points' => $newPoints,
                'time' => $request->time,
                'build_id' => $request->build_id,
            ]);
        } else {
            // Crear un NUEVO registro pendiente (el aprobado antiguo sigue visible)
            Score::create([
                'user_id' => $userId,
                'character_id' => $characterId,
                'build_id' => $request->build_id,
                'points' => $newPoints,
                'time' => $request->time,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', '¡Puntuación enviada! Tu récord está en proceso de revisión por el equipo de administración.');
    }
}
