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
            'id' => 'required|string|max:255|unique:items,id',
            'name' => 'required|string|max:255|unique:items,name',
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

    /**
     * Muestra el catálogo de ítems con filtrado por tipo para el panel de administración.
     */
    public function mostrarCatalogo(Request $request)
    {
        $tipoFiltro = $request->input('type');

        $consulta = Item::orderBy('type')->orderBy('name');

        if ($tipoFiltro && in_array($tipoFiltro, ['personaje', 'arma', 'tomo', 'item'])) {
            $consulta->where('type', $tipoFiltro);
        }

        $items = $consulta->paginate(20)->appends(['type' => $tipoFiltro]);

        return view('admin.catalogo.index', [
            'items' => $items,
            'tipoActual' => $tipoFiltro
        ]);
    }

    /**
     * Muestra el formulario de edición para un ítem existente.
     */
    public function mostrarFormularioEdicion($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.catalogo.form', ['item' => $item]);
    }

    /**
     * Valida y actualiza un ítem, opcionalmente reemplazando su imagen.
     */
    public function actualizarItem(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $datosValidados = $request->validate([
            'name' => 'required|string|max:255|unique:items,name,' . $id,
            'description' => 'nullable|string',
            'requirement' => 'nullable|string|max:255',
            'type' => 'required|in:arma,tomo,item,personaje',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datosActualizacion = [
            'name' => $datosValidados['name'],
            'description' => $datosValidados['description'] ?? null,
            'requirement' => $datosValidados['requirement'] ?? null,
            'type' => $datosValidados['type'],
        ];

        // Si se sube una nueva imagen, reemplazar la anterior
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior del storage si existía
            if ($item->image_path && \Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
                Storage::disk('public')->delete($item->image_path);
            }
            $datosActualizacion['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($datosActualizacion);

        return redirect()->route('admin.catalogo.index', ['type' => $item->type])
            ->with('success', 'Ítem "' . $item->name . '" actualizado correctamente.');
    }

    /**
     * Elimina un ítem y su imagen del storage.
     */
    public function eliminarItem($id)
    {
        $item = Item::findOrFail($id);

        // Eliminar imagen del storage si existe
        if ($item->image_path && \Illuminate\Support\Str::startsWith($item->image_path, 'items/')) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Ítem eliminado permanentemente.');
    }
}
