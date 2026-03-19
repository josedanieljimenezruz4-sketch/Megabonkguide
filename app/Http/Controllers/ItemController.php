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
}
