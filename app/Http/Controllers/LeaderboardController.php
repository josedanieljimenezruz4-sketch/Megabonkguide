<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Item;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $difficulty = $request->get('difficulty', 'bonk10');
        $characterId = $request->get('character', 'all');

        $query = Score::with(['user', 'character', 'build'])
            ->whereIn('status', ['approved', 'pending'])
            ->where('difficulty', $difficulty);

        if ($characterId !== 'all') {
            $query->where('character_id', $characterId);
        }

        $scores = $query->orderBy('points', 'desc')->take(50)->get();
        $characters = Item::where('type', 'personaje')->get();

        return view('leaderboard', compact('scores', 'characters', 'difficulty', 'characterId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'character_id' => 'required|string|exists:items,id',
            'difficulty' => 'required|string',
            'points' => 'required|integer|min:0',
            'time' => 'required|string',
            'build_id' => 'nullable|exists:builds,id'
        ]);

        $userId = auth()->id();
        $characterId = $request->character_id;
        $difficulty = $request->difficulty;
        $newPoints = $request->points;

        // Comprobar si hay un score antiguo o pendiente mejor
        $existingBest = Score::where('user_id', $userId)
            ->where('character_id', $characterId)
            ->where('difficulty', $difficulty)
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('points', 'desc')
            ->first();

        if ($existingBest && $existingBest->points >= $newPoints) {
            return back()->with('error', 'Ya tienes un récord (aprobado o pendiente) con igual o mayor puntuación (' . $existingBest->points . ') en esta categoría.');
        }

        if ($existingBest && !$request->has('confirm_override')) {
            return back()->withInput()->with('requires_confirmation', true)
                         ->with('confirmation_msg', "Tienes un récord previo de {$existingBest->points}. ¿Deseas enviar este nuevo de {$newPoints} para que sustituya al anterior?");
        }

        // Si llegó hasta aquí, borramos el viejo récord si existía (ya que vamos a crear uno mejor)
        if ($existingBest) {
            Score::where('user_id', $userId)
                ->where('character_id', $characterId)
                ->where('difficulty', $difficulty)
                ->delete();
        }

        // Crear el nuevo score directamente como approved
        Score::create([
            'user_id' => $userId,
            'character_id' => $characterId,
            'build_id' => $request->build_id,
            'points' => $newPoints,
            'time' => $request->time,
            'difficulty' => $difficulty,
            'status' => 'approved'
        ]);

        return back()->with('success', '¡Puntuación enviada y publicada con éxito en el Leaderboard!');
    }
}
