<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameDataController extends Controller
{
    public function tierlist(\Illuminate\Http\Request $request)
    {
        $category = $request->get('category');
        $sort = $request->get('sort');
        
        $query = \App\Models\Item::query();

        if ($category) {
            $query->where('type', $category);
        }

        if ($sort === 'popularity') {
            $query->orderBy('votes', 'desc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $allItems = $query->get();
        $itemsByRank = $allItems->whereNotNull('rank')->groupBy('rank');
        $pendingItems = $allItems->whereNull('rank');

        $pendingItemIds = $pendingItems->pluck('id')->toArray();
        $rankVotes = \Illuminate\Support\Facades\DB::table('user_rank_votes')
            ->select('item_id', 'suggested_rank', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereIn('item_id', $pendingItemIds)
            ->groupBy('item_id', 'suggested_rank')
            ->get();
            
        $mostVotedRanks = [];
        $votesGrouped = collect($rankVotes)->groupBy('item_id');
        foreach ($votesGrouped as $itemId => $votes) {
            $mostVoted = $votes->sortByDesc('total')->first();
            $mostVotedRanks[$itemId] = $mostVoted->suggested_rank;
        }

        $recentCommunityTierLists = \App\Models\TierList::with(['user', 'rows.item'])->latest()->take(5)->get();

        return view('tierlist', compact('itemsByRank', 'pendingItems', 'mostVotedRanks', 'category', 'sort', 'recentCommunityTierLists'));
    }

    public function voteItem(\Illuminate\Http\Request $request, $id)
    {
        $item = \App\Models\Item::findOrFail($id);
        
        $userId = auth()->id();
        
        $exists = \Illuminate\Support\Facades\DB::table('item_user_votes')
            ->where('user_id', $userId)
            ->where('item_id', $id)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => '¡Solo puedes votar una vez por este item!'
            ]);
        }
        
        \Illuminate\Support\Facades\DB::table('item_user_votes')->insert([
            'user_id' => $userId,
            'item_id' => $id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $item->increment('votes');

        return response()->json([
            'success' => true,
            'votes' => $item->votes
        ]);
    }

    public function voteRankItem(\Illuminate\Http\Request $request, $id)
    {
        $item = \App\Models\Item::findOrFail($id);
        
        $request->validate([
            'rank' => 'required|in:S,A,B,C,D,E,F'
        ]);
        
        $userId = auth()->id();
        $rank = $request->input('rank');
        
        \Illuminate\Support\Facades\DB::table('user_rank_votes')->updateOrInsert(
            ['user_id' => $userId, 'item_id' => $id],
            ['suggested_rank' => $rank, 'updated_at' => now()]
        );
        
        $mostVoted = \Illuminate\Support\Facades\DB::table('user_rank_votes')
            ->select('suggested_rank', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->where('item_id', $id)
            ->groupBy('suggested_rank')
            ->orderBy('total', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'most_voted_rank' => $mostVoted ? $mostVoted->suggested_rank : $rank
        ]);
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
