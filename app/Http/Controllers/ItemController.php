<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo ítem en la base de datos.
     */
    public function mostrarFormularioCreacion()
    {
        return view('admin.items.create');
    }

    /**
     * Valida y guarda un nuevo ítem, incluyendo la subida de su imagen.
     */
    public function guardarItem(Request $request)
    {
        $datosValidados = $request->validate([
            'id' => 'required|string|max:255|unique:items',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirement' => 'nullable|string|max:255',
            'type' => 'required|in:arma,tomo,item,personaje',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('image')) {
            // Storage guarda el archivo físicamente en: storage/app/public/items/
            $rutaImagen = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'id' => $datosValidados['id'],
            'name' => $datosValidados['name'],
            'description' => $datosValidados['description'],
            'requirement' => $datosValidados['requirement'] ?? null,
            'type' => $datosValidados['type'],
            'image_path' => $rutaImagen,
        ]);

        return redirect()->back()->with('success', 'Ítem guardado con éxito. Su imagen ahora es pública.');
    }

    /**
     * Asigna permanentemente un rango a un ítem individual (vía AJAX).
     */
    public function aprobarRango(Request $request, $id)
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

    /**
     * Asigna un rango a múltiples ítems de forma masiva (vía AJAX).
     */
    public function aprobacionMasiva(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:items,id',
            'rank' => 'required|in:S,A,B,C,PENDING'
        ]);

        $ids = $request->input('ids');
        $rangoDestino = $request->input('rank');
        
        $rangoBaseDatos = ($rangoDestino === 'PENDING') ? null : $rangoDestino;

        Item::whereIn('id', $ids)->update(['rank' => $rangoBaseDatos]);

        $mensaje = ($rangoDestino === 'PENDING') 
            ? count($ids) . " ítems han sido devueltos a pendientes (Laboratorio)." 
            : count($ids) . " ítems han sido asignados al rango {$rangoDestino} masivamente.";

        return response()->json([
            'success' => true,
            'message' => $mensaje
        ]);
    }
}
