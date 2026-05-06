<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TierList;
use App\Models\TierListRow;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class UserTierListController extends Controller
{
    /**
     * Muestra la lista paginada de Tier Lists creadas por la comunidad.
     */
    public function mostrarIndiceDeTierLists(Request $request)
    {
        $categoriaFiltro = $request->get('categoria');

        $consulta = TierList::with(['user', 'rows.item'])->latest();

        if ($categoriaFiltro && $categoriaFiltro !== 'general' && $categoriaFiltro !== 'todos') {
            $consulta->where('categoria', $categoriaFiltro);
        }

        $tierListsPaginadas = $consulta->paginate(12);

        return view('community_tierlists.index', [
            'tierLists' => $tierListsPaginadas,
            'categoria' => $categoriaFiltro
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva Tier List.
     */
    public function crearTierList(Request $request)
    {
        $categoriaSeleccionada = $request->get('categoria', 'personaje'); // Por defecto 'personaje'
        
        if ($categoriaSeleccionada === 'general' || $categoriaSeleccionada === 'todo') {
            $elementosDisponibles = Item::all();
        } else {
            $elementosDisponibles = Item::where('type', $categoriaSeleccionada)->get();
        }
        
        return view('community_tierlists.create', [
            'items' => $elementosDisponibles,
            'categoria' => $categoriaSeleccionada
        ]);
    }

    /**
     * Valida y guarda en la base de datos la nueva Tier List de la comunidad.
     */
    public function guardarTierList(Request $request)
    {
        $datosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string',
            'ranks' => 'array',
            'ranks.*' => 'nullable|in:S,A,B,C,D,E,F',
        ]);

        $nuevaTierList = TierList::create([
            'user_id' => Auth::id(),
            'titulo' => $datosValidados['titulo'],
            'categoria' => $datosValidados['categoria'],
            'descripcion' => $datosValidados['descripcion'] ?? null,
        ]);

        $rangosAsignados = $request->input('ranks', []);
        foreach ($rangosAsignados as $itemId => $rango) {
            if (!empty($rango) && in_array($rango, ['S', 'A', 'B', 'C', 'D', 'E', 'F'])) {
                TierListRow::create([
                    'tier_list_id' => $nuevaTierList->id,
                    'item_id' => $itemId,
                    'rank' => $rango,
                ]);
            }
        }

        return redirect()->route('community-tierlists.show', $nuevaTierList->id)
                         ->with('success', '¡Tier List creada exitosamente!');
    }

    /**
     * Muestra el detalle de una Tier List específica y sus comentarios.
     */
    public function mostrarTierListDetallada($id)
    {
        $tierList = TierList::with(['user', 'rows.item', 'comments' => function($consulta) {
            $consulta->whereNull('parent_id')->with(['user', 'replies.user']);
        }])->findOrFail($id);
        
        // Agrupar items por rango para la vista
        $elementosPorRango = [
            'S' => collect(),
            'A' => collect(),
            'B' => collect(),
            'C' => collect(),
            'D' => collect(),
            'E' => collect(),
            'F' => collect()
        ];

        foreach ($tierList->rows as $fila) {
            if ($fila->item) {
                $elementosPorRango[$fila->rank]->push($fila->item);
            }
        }

        return view('community_tierlists.show', [
            'tierList' => $tierList,
            'itemsByRank' => $elementosPorRango
        ]);
    }

    /**
     * Acción de administrador para eliminar permanentemente una Tier List.
     */
    public function eliminarTierListAdmin($id)
    {
        $tierList = TierList::findOrFail($id);
        $tierList->delete();

        return redirect()->route('admin.community-tierlists.index')
                         ->with('success', 'La Tier List ha sido eliminada permanentemente.');
    }
}
