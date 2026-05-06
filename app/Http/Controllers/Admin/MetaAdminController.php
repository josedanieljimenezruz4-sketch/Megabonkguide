<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaStrategy;
use App\Models\PatchNote;

class MetaAdminController extends Controller
{
    /**
     * Muestra el panel de administración del Meta (Estrategias y Notas de parche).
     */
    public function mostrarMetaAdmin()
    {
        $estrategias = MetaStrategy::orderBy('created_at', 'desc')->get();
        $notasDeParche = PatchNote::orderBy('created_at', 'desc')->get();

        return view('admin.meta', [
            'estrategias' => $estrategias,
            'patchNotes' => $notasDeParche
        ]);
    }

    /**
     * Guarda una nueva estrategia Meta en la base de datos.
     */
    public function guardarEstrategia(Request $request)
    {
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'build_type' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        MetaStrategy::create([
            'title' => $datosValidados['title'],
            'description' => $datosValidados['description'],
            'build_type' => $datosValidados['build_type'],
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Estrategia creada exitosamente.');
    }

    /**
     * Actualiza una estrategia Meta existente.
     */
    public function actualizarEstrategia(Request $request, $id)
    {
        $estrategia = MetaStrategy::findOrFail($id);
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'build_type' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $estrategia->update([
            'title' => $datosValidados['title'],
            'description' => $datosValidados['description'],
            'build_type' => $datosValidados['build_type'],
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Estrategia actualizada.');
    }

    /**
     * Elimina permanentemente una estrategia Meta.
     */
    public function eliminarEstrategia($id)
    {
        MetaStrategy::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Estrategia eliminada.');
    }

    /**
     * Guarda una nueva nota de parche.
     */
    public function guardarNotaParche(Request $request)
    {
        $datosValidados = $request->validate([
            'version' => 'nullable|string|max:50',
            'change_type' => 'required|in:buff,nerf,new',
            'description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        PatchNote::create([
            'version' => $datosValidados['version'],
            'change_type' => $datosValidados['change_type'],
            'description' => $datosValidados['description'],
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Nota del parche añadida.');
    }

    /**
     * Actualiza una nota de parche existente.
     */
    public function actualizarNotaParche(Request $request, $id)
    {
        $nota = PatchNote::findOrFail($id);
        $datosValidados = $request->validate([
            'version' => 'nullable|string|max:50',
            'change_type' => 'required|in:buff,nerf,new',
            'description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $nota->update([
            'version' => $datosValidados['version'],
            'change_type' => $datosValidados['change_type'],
            'description' => $datosValidados['description'],
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Nota del parche actualizada.');
    }

    /**
     * Elimina permanentemente una nota de parche.
     */
    public function eliminarNotaParche($id)
    {
        PatchNote::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Nota eliminada.');
    }
}
