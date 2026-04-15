<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;

class BuildController extends Controller
{
    public function index(Request $request)
    {
        // Optimizamos N+1 con Eager Loading
        $query = Build::with(['character', 'items']);

        // Filtro por Texto en Título
        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });

        // Filtro por Personaje
        $query->when($request->filled('character_id'), function ($q) use ($request) {
            $q->where('character_id', $request->character_id);
        });

        // Filtro por Arma específica (usando whereHas sobre la tabla pivote)
        $query->when($request->filled('weapon_id'), function ($q) use ($request) {
            $q->whereHas('items', function ($query) use ($request) {
                $query->where('item_id', $request->weapon_id)
                      ->where('slot_type', 'Arma');
            });
        });

        // Filtro por Rating
        $query->when($request->filled('rating'), function ($q) use ($request) {
            $q->where('rating', $request->rating);
        });

        // Filtro por Tipo de Build
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        $builds = $query->latest()->paginate(15);
        // Mantenemos los parámetros en la URL de paginación
        $builds->appends($request->all());

        // Contadores dinámicos agrupados por tipo
        $counts = Build::selectRaw('type, count(*) as total_count')
                        ->groupBy('type')
                        ->pluck('total_count', 'type');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'builds' => $builds,
                'counts' => $counts
            ]);
        }

        return view('builds', compact('builds', 'counts'));
    }

    public function create()
    {
        $personajes = \App\Models\Item::where('type', 'personaje')->get();
        $armas = \App\Models\Item::where('type', 'arma')->get();
        $tomos = \App\Models\Item::where('type', 'tomo')->get();
        $accesorios = \App\Models\Item::where('type', 'item')->get();

        return view('builds.create', compact('personajes', 'armas', 'tomos', 'accesorios'));
    }

    public function store(Request $request)
    {
        // Validamos la lógica obligatoria de la tabla Builds
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'character_id' => 'required|exists:items,id',
            'description' => 'nullable|string',
            'rating' => 'integer|min:1|max:5',
            'type' => 'nullable|string',
            // Validamos que sea un array los items
            'items' => 'required|array',
            'items.Arma' => 'required|array|max:4',
            'items.Tomo' => 'required|array|max:4',
            'items.Item' => 'nullable|array|max:6',
        ]);

        // 1. Crear la Build
        $build = Build::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'character_id' => $validated['character_id'],
            'description' => $validated['description'] ?? null,
            'rating' => $validated['rating'] ?? 1,
            'type' => $validated['type'] ?? null,
        ]);

        // 2. Adjuntar los Items mediante Sync/Attach
        $pivotData = [];
        
        foreach (['Arma', 'Tomo', 'Item'] as $slot) {
            if (isset($validated['items'][$slot])) {
                foreach ($validated['items'][$slot] as $itemId) {
                    if ($itemId) { 
                        // Guardamos el attach asegurando la columna slot_type
                        // usamos un arreglo secuencial para que acepte items repetidos si fuera aplicable, o attach directamente.
                        // Usar attach en lugar de sync nos permite agregar múltiples sin claves asociativas complicadas
                        $build->items()->attach($itemId, ['slot_type' => $slot]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', '¡Build publicada en la base de datos con éxito!');
    }

    public function show(Build $build)
    {
        $build->load(['character', 'items']);

        $userVote = null;
        if (auth()->check()) {
            $voteRecord = \App\Models\BuildVote::where('build_id', $build->id)
                ->where('user_id', auth()->id())
                ->first();
            if ($voteRecord) {
                $userVote = $voteRecord->score;
            }
        }

        return view('builds.show', compact('build', 'userVote'));
    }

    public function vote(Request $request, Build $build)
    {
        $request->validate([
            'score' => 'required|numeric|min:1|max:5',
        ]);

        // Redondear a la media estrella más cercana (.0 o .5)
        $score = round($request->score * 2) / 2;

        \App\Models\BuildVote::updateOrCreate(
            ['user_id' => auth()->id(), 'build_id' => $build->id],
            ['score' => $score]
        );

        // Recalcular promedio
        $average = \App\Models\BuildVote::where('build_id', $build->id)->avg('score');
        $average = round($average, 1);
        
        $build->update(['rating' => $average]);

        return response()->json([
            'success' => true,
            'new_rating' => $average,
            'message' => '¡Voto registrado ('. str_replace('.', ',', $score) .'⭐) con éxito!'
        ]);
    }
}
