<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\TierList;

class ModerationController extends Controller
{
    public function index()
    {
        $builds = Build::with('user', 'character')->latest()->paginate(15, ['*'], 'builds_page');
        $tierlists = TierList::with('user')->latest()->paginate(15, ['*'], 'tierlists_page');

        return view('admin.moderation.index', compact('builds', 'tierlists'));
    }

    public function destroyBuild($id)
    {
        Build::findOrFail($id)->delete();
        return back()->with('success', 'Build eliminada por el administrador.');
    }

    public function destroyTierList($id)
    {
        TierList::findOrFail($id)->delete();
        return back()->with('success', 'TierList eliminada por el administrador.');
    }

    public function editBuild(Build $build)
    {
        $build->load('items');
        
        $personajes = \App\Models\Item::where('type', 'personaje')->get();
        $armas = \App\Models\Item::where('type', 'arma')->get();
        $tomos = \App\Models\Item::where('type', 'tomo')->get();
        $accesorios = \App\Models\Item::where('type', 'item')->get();
        $strategies = \App\Models\MetaStrategy::where('is_active', true)->get();

        return view('admin.moderation.edit_build', compact('build', 'personajes', 'armas', 'tomos', 'accesorios', 'strategies'));
    }

    public function updateBuild(Request $request, Build $build)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'character_id' => 'required|exists:items,id',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'meta_strategy_id' => 'nullable|exists:meta_strategies,id',
            'items' => 'required|array',
        ]);

        $build->update([
            'name' => $validated['name'],
            'character_id' => $validated['character_id'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'] ?? null,
            'meta_strategy_id' => $validated['meta_strategy_id'] ?? null,
        ]);

        $build->items()->detach();
        foreach (['Arma', 'Tomo', 'Item'] as $slot) {
            if (isset($validated['items'][$slot])) {
                foreach ($validated['items'][$slot] as $itemId) {
                    if ($itemId) { 
                        $build->items()->attach($itemId, ['slot_type' => $slot]);
                    }
                }
            }
        }

        return redirect()->route('admin.moderation.index')->with('success', 'Build actualizada correctamente.');
    }
}
