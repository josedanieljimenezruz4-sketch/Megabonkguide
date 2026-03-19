<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class UnlockController extends Controller
{
    public function toggleUnlock(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'is_checked' => 'required|boolean',
        ]);

        $user = $request->user();
        
        if ($request->is_checked) {
            $user->unlocks()->syncWithoutDetaching([$request->item_id]);
        } else {
            $user->unlocks()->detach($request->item_id);
        }

        return response()->json([
            'status' => 'success',
            'attached' => $request->is_checked,
            'detached' => !$request->is_checked
        ]);
    }

    private function getUnlockedItems()
    {
        return auth()->check() 
            ? DB::table('user_unlocks')->where('user_id', auth()->id())->pluck('item_id')->toArray() 
            : [];
    }

    public function index()
    {
        return view('unlocks');
    }

    private function getFilteredItems(Request $request, $type)
    {
        $unlockedItems = $this->getUnlockedItems();
        $query = Item::where('type', $type);

        if ($request->filter === 'completed') {
            $query->whereIn('id', $unlockedItems);
        } elseif ($request->filter === 'pending') {
            $query->whereNotIn('id', $unlockedItems);
        }

        $order = $request->order === 'desc' ? 'desc' : 'asc';
        $query->orderBy('name', $order);

        return $query->get();
    }

    public function weapons(Request $request)
    {
        return view('armas', [
            'unlockedItems' => $this->getUnlockedItems(),
            'items' => $this->getFilteredItems($request, 'arma')
        ]);
    }

    public function tomes(Request $request)
    {
        return view('tomos', [
            'unlockedItems' => $this->getUnlockedItems(),
            'items' => $this->getFilteredItems($request, 'tomo')
        ]);
    }

    public function items(Request $request)
    {
        return view('items', [
            'unlockedItems' => $this->getUnlockedItems(),
            'items' => $this->getFilteredItems($request, 'item')
        ]);
    }

    public function characters(Request $request)
    {
        return view('personajes', [
            'unlockedItems' => $this->getUnlockedItems(),
            'items' => $this->getFilteredItems($request, 'personaje')
        ]);
    }

    public function inventory()
    {
        $unlockedIds = $this->getUnlockedItems();
        
        // Carga los Items de la BD cuyo 'id' esté en el array del usuario
        // Trae Personajes, Armas, Tomos e Items indistintamente ordenados.
        $items = Item::whereIn('id', $unlockedIds)->orderBy('type')->get();

        return view('inventario', compact('items'));
    }
}
