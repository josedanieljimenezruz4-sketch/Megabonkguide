<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TierList;
use App\Models\TierListRow;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class UserTierListController extends Controller
{
    public function index(Request $request)
    {
        $categoria = $request->get('categoria');

        $query = TierList::with(['user', 'rows.item'])->latest();

        if ($categoria && $categoria !== 'general' && $categoria !== 'todos') {
            $query->where('categoria', $categoria);
        }

        $tierLists = $query->paginate(12);

        return view('community_tierlists.index', compact('tierLists', 'categoria'));
    }

    public function create(Request $request)
    {
        $categoria = $request->get('categoria', 'personaje'); // Default to 'personaje'
        
        if ($categoria === 'general' || $categoria === 'todo') {
            $items = Item::all();
        } else {
            $items = Item::where('type', $categoria)->get();
        }
        
        return view('community_tierlists.create', compact('items', 'categoria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string',
            'ranks' => 'array',
            'ranks.*' => 'nullable|in:S,A,B,C,D,E,F',
        ]);

        $tierList = TierList::create([
            'user_id' => Auth::id(),
            'titulo' => $request->input('titulo'),
            'categoria' => $request->input('categoria'),
            'descripcion' => $request->input('descripcion'),
        ]);

        $ranks = $request->input('ranks', []);
        foreach ($ranks as $itemId => $rank) {
            if (!empty($rank) && in_array($rank, ['S', 'A', 'B', 'C', 'D', 'E', 'F'])) {
                TierListRow::create([
                    'tier_list_id' => $tierList->id,
                    'item_id' => $itemId,
                    'rank' => $rank,
                ]);
            }
        }

        return redirect()->route('community-tierlists.show', $tierList->id)
                         ->with('success', '¡Tier List creada exitosamente!');
    }

    public function show($id)
    {
        $tierList = TierList::with(['user', 'rows.item', 'comments' => function($q) {
            $q->whereNull('parent_id')->with(['user', 'replies.user']);
        }])->findOrFail($id);
        
        // Agrupar items por rango
        $itemsByRank = [
            'S' => collect(),
            'A' => collect(),
            'B' => collect(),
            'C' => collect(),
            'D' => collect(),
            'E' => collect(),
            'F' => collect()
        ];

        foreach ($tierList->rows as $row) {
            if ($row->item) {
                $itemsByRank[$row->rank]->push($row->item);
            }
        }

        return view('community_tierlists.show', compact('tierList', 'itemsByRank'));
    }

    public function destroyAdmin($id)
    {
        $tierList = TierList::findOrFail($id);
        $tierList->delete();

        return redirect()->route('admin.community-tierlists.index')
                         ->with('success', 'La Tier List ha sido eliminada permanentemente.');
    }
}
