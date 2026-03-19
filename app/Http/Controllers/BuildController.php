<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;

class BuildController extends Controller
{
    public function store(Request $request)
    {
        // Validamos la lógica obligatoria de la tabla Builds
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'character_id' => 'required|exists:items,id',
            'weapon_1_id' => 'required|exists:items,id',
            'weapon_2_id' => 'required|exists:items,id',
            'tome_1_id' => 'required|exists:items,id',
            'tome_2_id' => 'required|exists:items,id',
            // Opcionales
            'weapon_3_id' => 'nullable|exists:items,id',
            'weapon_4_id' => 'nullable|exists:items,id',
            'tome_3_id' => 'nullable|exists:items,id',
            'tome_4_id' => 'nullable|exists:items,id',
        ]);

        // Asignamos el ID del usuario directamente por seguridad Backend
        $validated['user_id'] = auth()->id();
        
        Build::create($validated);

        return redirect()->back()->with('success', '¡Build publicada en la base de datos con éxito!');
    }
}
