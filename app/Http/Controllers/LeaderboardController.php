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

        $existingScore = Score::where('user_id', $userId)
            ->where('character_id', $characterId)
            ->where('difficulty', $difficulty)
            ->first();

        if ($existingScore) {
            if ($newPoints > $existingScore->points) {
                $existingScore->update([
                    'points' => $newPoints,
                    'time' => $request->time,
                    'build_id' => $request->build_id,
                    'status' => 'approved'
                ]);
                return back()->with('success', '¡Nuevo récord personal establecido!');
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
            'difficulty' => $difficulty,
            'status' => 'approved'
        ]);

        return back()->with('success', '¡Nuevo récord personal establecido!');
    }
}
