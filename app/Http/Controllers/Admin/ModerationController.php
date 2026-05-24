<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\CommunityPost;

class ModerationController extends Controller
{
    /**
     * Muestra el panel de moderación para Builds y Posts de comunidad.
     */
    public function mostrarPanelModeracion()
    {
        $buildsPaginadas = Build::with('user', 'character')->latest()->paginate(15, ['*'], 'builds_page');
        $postsPaginados = CommunityPost::with('user')->latest()->paginate(15, ['*'], 'posts_page');

        return view('admin.moderation.index', [
            'builds' => $buildsPaginadas,
            'posts' => $postsPaginados
        ]);
    }

    /**
     * Elimina permanentemente una Build por parte de moderación.
     */
    public function eliminarBuild($id)
    {
        Build::findOrFail($id)->delete();
        return back()->with('success', 'Build eliminada por el administrador.');
    }

    /**
     * Elimina permanentemente una TierList por parte de moderación.
     */
    public function eliminarTierList($id)
    {
        TierList::findOrFail($id)->delete();
        return back()->with('success', 'TierList eliminada por el administrador.');
    }

    /**
     * Carga el formulario de edición para una Build.
     */
    public function editarBuild(Build $build)
    {
        $build->load('items');
        
        $personajes = \App\Models\Item::where('type', 'personaje')->get();
        $armas = \App\Models\Item::where('type', 'arma')->get();
        $tomos = \App\Models\Item::where('type', 'tomo')->get();
        $accesorios = \App\Models\Item::where('type', 'item')->get();
        $estrategias = \App\Models\MetaStrategy::where('is_active', true)->get();

        return view('admin.moderation.edit_build', [
            'build' => $build,
            'personajes' => $personajes,
            'armas' => $armas,
            'tomos' => $tomos,
            'accesorios' => $accesorios,
            'strategies' => $estrategias
        ]);
    }

    /**
     * Actualiza la información y el equipamiento de una Build.
     */
    public function actualizarBuild(Request $request, Build $build)
    {
        $datosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'character_id' => 'required|exists:items,id',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'meta_strategy_id' => 'nullable|exists:meta_strategies,id',
            'items' => 'required|array',
        ]);

        $build->update([
            'name' => $datosValidados['name'],
            'character_id' => $datosValidados['character_id'],
            'description' => $datosValidados['description'] ?? null,
            'type' => $datosValidados['type'] ?? null,
            'meta_strategy_id' => $datosValidados['meta_strategy_id'] ?? null,
        ]);

        $build->items()->detach();
        foreach (['Arma', 'Tomo', 'Item'] as $slot) {
            if (isset($datosValidados['items'][$slot])) {
                foreach ($datosValidados['items'][$slot] as $itemId) {
                    if ($itemId) { 
                        $build->items()->attach($itemId, ['slot_type' => $slot]);
                    }
                }
            }
        }

        return redirect()->route('admin.moderation.index')->with('success', 'Build actualizada correctamente.');
    }

    /**
     * Elimina permanentemente un post de la comunidad y su contenido asociado.
     */
    public function eliminarPost($id)
    {
        $post = CommunityPost::findOrFail($id);
        // Eliminar likes y comentarios asociados
        $post->likes()->detach();
        $post->comments()->delete();
        $post->delete();
        return back()->with('success', 'Post de comunidad eliminado correctamente.');
    }
}
