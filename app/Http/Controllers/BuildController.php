<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\Item;
use App\Models\BuildVote;
use App\Models\MetaStrategy;

class BuildController extends Controller
{
    /**
     * Muestra el listado paginado de builds con filtros y contadores por tipo.
     */
    public function mostrarListaDeBuilds(Request $request)
    {
        // Optimizamos el problema N+1 con Eager Loading
        $consulta = Build::with(['character', 'items', 'user']);

        // Filtro por texto en el título
        $consulta->when($request->filled('search'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });

        // Filtro por personaje
        $consulta->when($request->filled('character_id'), function ($q) use ($request) {
            $q->where('character_id', $request->character_id);
        });

        // Filtro por arma específica (usando whereHas sobre la tabla pivote)
        $consulta->when($request->filled('weapon_id'), function ($q) use ($request) {
            $q->whereHas('items', function ($subConsulta) use ($request) {
                $subConsulta->where('item_id', $request->weapon_id)
                      ->where('slot_type', 'Arma');
            });
        });

        // Filtro por valoración
        $consulta->when($request->filled('rating'), function ($q) use ($request) {
            $q->where('rating', $request->rating);
        });

        // Filtro por tipo de build (DPS, Tanque, Soporte)
        $consulta->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        $builds = $consulta->latest()->paginate(15);
        // Mantenemos los parámetros en la URL de paginación
        $builds->appends($request->all());

        // Contadores dinámicos agrupados por tipo
        $contadores = Build::selectRaw('type, count(*) as total_count')
                        ->groupBy('type')
                        ->pluck('total_count', 'type');

        // Si es una petición AJAX, devolvemos JSON para Alpine.js
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'builds' => $builds,
                'counts' => $contadores
            ]);
        }

        return view('builds', ['builds' => $builds, 'counts' => $contadores]);
    }

    /**
     * Muestra el formulario para crear una nueva build.
     */
    public function crearBuild()
    {
        $personajes = Item::where('type', 'personaje')->get();
        $armas = Item::where('type', 'arma')->get();
        $tomos = Item::where('type', 'tomo')->get();
        $accesorios = Item::where('type', 'item')->get();
        $strategies = MetaStrategy::where('is_active', true)->get();

        return view('builds.create', compact('personajes', 'armas', 'tomos', 'accesorios', 'strategies'));
    }

    /**
     * Valida y guarda una nueva build en la base de datos, adjuntando los ítems seleccionados.
     */
    public function guardarBuild(Request $request)
    {
        // Validamos los campos obligatorios de la tabla builds
        $datosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'character_id' => 'required|exists:items,id',
            'description' => 'nullable|string',
            'rating' => 'integer|min:1|max:5',
            'type' => 'nullable|string',
            'meta_strategy_id' => 'nullable|exists:meta_strategies,id',
            // Validamos que los items sean arrays
            'items' => 'required|array',
            'items.Arma' => 'required|array|max:4',
            'items.Tomo' => 'required|array|max:4',
            'items.Item' => 'nullable|array|max:6',
        ]);

        // 1. Crear la Build en la base de datos
        $build = Build::create([
            'user_id' => auth()->id(),
            'name' => $datosValidados['name'],
            'character_id' => $datosValidados['character_id'],
            'description' => $datosValidados['description'] ?? null,
            'rating' => $datosValidados['rating'] ?? 1,
            'type' => $datosValidados['type'] ?? null,
            'meta_strategy_id' => $datosValidados['meta_strategy_id'] ?? null,
        ]);

        // 2. Adjuntar los Items mediante Attach (permite duplicados entre tipos)
        foreach (['Arma', 'Tomo', 'Item'] as $tipoRanura) {
            if (isset($datosValidados['items'][$tipoRanura])) {
                foreach ($datosValidados['items'][$tipoRanura] as $itemId) {
                    if ($itemId) { 
                        $build->items()->attach($itemId, ['slot_type' => $tipoRanura]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', '¡Build publicada en la base de datos con éxito!');
    }

    /**
     * Muestra el detalle completo de una build específica, incluyendo el voto del usuario actual.
     */
    public function mostrarBuild(Build $build)
    {
        $build->load(['character', 'items', 'user']);

        // Comprobar si el usuario logueado ya ha votado esta build
        $votoUsuario = null;
        if (auth()->check()) {
            $registroVoto = BuildVote::where('build_id', $build->id)
                ->where('user_id', auth()->id())
                ->first();
            if ($registroVoto) {
                $votoUsuario = $registroVoto->score;
            }
        }

        return view('builds.show', ['build' => $build, 'userVote' => $votoUsuario]);
    }

    /**
     * Registra o actualiza el voto de un usuario sobre una build y recalcula el promedio.
     */
    public function votarBuild(Request $request, Build $build)
    {
        $request->validate([
            'score' => 'required|numeric|min:1|max:5',
        ]);

        // Redondear a la media estrella más cercana (.0 o .5)
        $puntuacion = round($request->score * 2) / 2;

        BuildVote::updateOrCreate(
            ['user_id' => auth()->id(), 'build_id' => $build->id],
            ['score' => $puntuacion]
        );

        // Recalcular el promedio global de la build
        $promedio = BuildVote::where('build_id', $build->id)->avg('score');
        $promedio = round($promedio, 1);
        
        $build->update(['rating' => $promedio]);

        return response()->json([
            'success' => true,
            'new_rating' => $promedio,
            'message' => '¡Voto registrado ('. str_replace('.', ',', $puntuacion) .'⭐) con éxito!'
        ]);
    }
}
