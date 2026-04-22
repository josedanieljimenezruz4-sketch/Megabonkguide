<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:255|unique:items',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirement' => 'nullable|string|max:255',
            'type' => 'required|in:arma,tomo,item,personaje',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Storage guarda el archivo físicamente en: storage/app/public/items/
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'id' => $validated['id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'requirement' => $validated['requirement'] ?? null,
            'type' => $validated['type'],
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Ítem guardado con éxito. Su imagen ahora es pública.');
    }

    public function approveRank(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $request->validate([
            'rank' => 'required|in:S,A,B,C,D,E,F,PENDING'
        ]);

        $item->rank = $request->input('rank');
        $item->save();

        return response()->json([
            'success' => true,
            'message' => "Rango {$item->rank} asignado permanentemente al ítem."
        ]);
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:items,id',
            'rank' => 'required|in:S,A,B,C,PENDING'
        ]);

        $ids = $request->input('ids');
        $rank = $request->input('rank');
        
        $dbRank = ($rank === 'PENDING') ? null : $rank;

        Item::whereIn('id', $ids)->update(['rank' => $dbRank]);

        $msg = ($rank === 'PENDING') 
            ? count($ids) . " ítems han sido devueltos a pendientes (Laboratorio)." 
            : count($ids) . " ítems han sido asignados al rango {$rank} masivamente.";

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }
}
