<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class UnlockController extends Controller
{
    // Añade o elimina un objeto del inventario del usuario según su estado.
    public function alternarEstadoDesbloqueo(Request $request)
    {
        try {
            $request->validate([
                'item_id' => 'required|string',
                'is_checked' => 'required|boolean',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Debes estar autenticado'], 401);
            }

            $itemId = $request->item_id;
            
            if ($request->is_checked) {
                // syncWithoutDetaching evita duplicados y asegura que esté en la tabla
                $user->unlocks()->syncWithoutDetaching([$itemId]);
                $message = '✨ Desbloqueado correctamente';
            } else {
                $user->unlocks()->detach($itemId);
                $message = '❌ Bloqueado correctamente';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'is_checked' => $request->is_checked
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en alternarEstadoDesbloqueo: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un problema al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Devuelve un array con los IDs de los elementos que el usuario posee.
    private function obtenerElementosDesbloqueados()
    {
        return auth()->check() 
            ? DB::table('user_unlocks')->where('user_id', auth()->id())->pluck('item_id')->toArray() 
            : [];
    }

    // Muestra la vista principal de la sección de desbloqueos.
    public function mostrarIndiceUnlocks()
    {
        return view('unlocks');
    }

    // Filtra los objetos de la base de datos según si están bloqueados/desbloqueados y su tipo.
    private function obtenerElementosFiltrados(Request $request, $type)
    {
        $unlockedItems = $this->obtenerElementosDesbloqueados();
        $query = Item::where('type', $type);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filter === 'completed') {
            $query->whereIn('id', $unlockedItems);
        } elseif ($request->filter === 'pending') {
            $query->whereNotIn('id', $unlockedItems);
        }

        $order = $request->order === 'desc' ? 'desc' : 'asc';
        $query->orderBy('name', $order);

        return $query->get();
    }

    // Carga la vista con la lista de armas disponibles y el estado del usuario.
    public function mostrarArmas(Request $request)
    {
        return view('armas', [
            'unlockedItems' => $this->obtenerElementosDesbloqueados(),
            'items' => $this->obtenerElementosFiltrados($request, 'arma')
        ]);
    }

    // Carga la vista con la lista de tomos disponibles y el estado del usuario.
    public function mostrarTomos(Request $request)
    {
        return view('tomos', [
            'unlockedItems' => $this->obtenerElementosDesbloqueados(),
            'items' => $this->obtenerElementosFiltrados($request, 'tomo')
        ]);
    }

    // Carga la vista con la lista de objetos disponibles y el estado del usuario.
    public function mostrarObjetos(Request $request)
    {
        return view('items', [
            'unlockedItems' => $this->obtenerElementosDesbloqueados(),
            'items' => $this->obtenerElementosFiltrados($request, 'item')
        ]);
    }

    // Carga la vista con la lista de personajes disponibles y el estado del usuario.
    public function mostrarPersonajes(Request $request)
    {
        return view('personajes', [
            'unlockedItems' => $this->obtenerElementosDesbloqueados(),
            'items' => $this->obtenerElementosFiltrados($request, 'personaje')
        ]);
    }

    // Muestra todos los elementos desbloqueados agrupados en el inventario personal.
    public function mostrarInventarioUnificado()
    {
        $unlockedIds = $this->obtenerElementosDesbloqueados();
        
        // Carga los Items de la BD cuyo 'id' esté en el array del usuario
        // Trae Personajes, Armas, Tomos e Items indistintamente ordenados.
        $items = Item::whereIn('id', $unlockedIds)->orderBy('type')->get();

        return view('inventario', compact('items'));
    }
}
