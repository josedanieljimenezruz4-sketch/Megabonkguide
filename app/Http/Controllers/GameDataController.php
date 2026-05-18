<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Build;
use App\Models\MetaStrategy;
use App\Models\MetaStrategyVote;
use App\Models\PatchNote;
use App\Models\TierList;
use App\Models\Update;

class GameDataController extends Controller
{
    /**
     * Muestra la Tier List oficial con los ítems organizados por rango y los votos pendientes.
     */
    public function mostrarTierlist(Request $request)
    {
        $categoria = $request->get('category');
        $ordenar = $request->get('sort');
        
        $consulta = Item::query();

        // Filtro por categoría de elemento
        if ($categoria) {
            $consulta->where('type', $categoria);
        }

        // Ordenación: por popularidad o alfabética
        if ($ordenar === 'popularity') {
            $consulta->orderBy('votes', 'desc');
        } else {
            $consulta->orderBy('name', 'asc');
        }

        $todosLosElementos = $consulta->get();
        $elementosPorRango = $todosLosElementos->whereNotNull('rank')->groupBy('rank');
        $elementosPendientes = $todosLosElementos->whereNull('rank');

        // Determinar el rango sugerido por la comunidad para cada ítem pendiente
        $rangosMasVotados = $this->obtenerRangosMasVotados($elementosPendientes->pluck('id')->toArray());

        // Obtener las últimas Tier Lists de la comunidad para mostrar en el sidebar
        $tierListsRecientes = TierList::with(['user', 'rows.item'])->latest()->take(5)->get();

        // Sugerencias del usuario actual (indexadas por item_id para búsqueda rápida)
        $sugerenciasUsuario = [];
        if (auth()->check()) {
            $sugerenciasUsuario = \App\Models\TierSuggestion::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'approved'])
                ->pluck('suggested_tier', 'item_id')
                ->toArray();
        }

        return view('tierlist', [
            'allItems' => $todosLosElementos,
            'itemsByRank' => $elementosPorRango,
            'pendingItems' => $elementosPendientes,
            'mostVotedRanks' => $rangosMasVotados,
            'category' => $categoria,
            'sort' => $ordenar,
            'recentCommunityTierLists' => $tierListsRecientes,
            'userSuggestions' => $sugerenciasUsuario,
        ]);
    }

    /**
     * Calcula y devuelve el rango más votado para un array de IDs de ítems.
     */
    private function obtenerRangosMasVotados(array $idsPendientes): array
    {
        if (empty($idsPendientes)) {
            return [];
        }

        $votosDeRango = DB::table('tier_suggestions')
            ->select('item_id', 'suggested_tier as suggested_rank', DB::raw('count(*) as total'))
            ->whereIn('item_id', $idsPendientes)
            ->where('status', 'pending')
            ->groupBy('item_id', 'suggested_tier')
            ->get();
            
        $rangosMasVotados = [];
        $votosAgrupados = collect($votosDeRango)->groupBy('item_id');
        foreach ($votosAgrupados as $itemId => $votos) {
            $masVotado = $votos->sortByDesc('total')->first();
            $rangosMasVotados[$itemId] = $masVotado->suggested_rank;
        }
        
        return $rangosMasVotados;
    }

    /**
     * Registra o actualiza el voto de rango sugerido por un usuario para un ítem.
     */
    public function votarRangoElemento(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $request->validate([
            'rank' => 'required|in:S,A,B,C,D,E,F'
        ]);
        
        $idUsuario = auth()->id();
        $rango = $request->input('rank');
        
        // Crear o actualizar la sugerencia del usuario para este ítem
        \App\Models\TierSuggestion::updateOrCreate(
            ['user_id' => $idUsuario, 'item_id' => $id],
            ['suggested_tier' => $rango, 'status' => 'pending']
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Sugerencia de rango registrada correctamente.'
        ]);
    }

    /**
     * Muestra la vista del Meta con estrategias activas, notas de parche y tendencias de personajes.
     */
    public function mostrarMeta()
    {
        $estrategias = MetaStrategy::where('is_active', true)->with('votes')->get();
        $notasDeParche = PatchNote::where('is_active', true)->latest()->get();

        // Calcular las tendencias de uso de personajes en los últimos 7 días
        $haceSieteDias = now()->subDays(7);
        $totalBuildsSemana = Build::where('created_at', '>=', $haceSieteDias)->count();
        $totalBuildsSemana = $totalBuildsSemana > 0 ? $totalBuildsSemana : 1; // Evitar división por cero

        $tendenciasEnBruto = Build::select('character_id', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $haceSieteDias)
            ->groupBy('character_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        $tendencias = [];
        foreach ($tendenciasEnBruto as $tendencia) {
            $personaje = Item::where('id', $tendencia->character_id)->first();
            if ($personaje) {
                $porcentaje = round(($tendencia->count / $totalBuildsSemana) * 100);
                $tendencias[] = [
                    'character' => $personaje,
                    'count' => $tendencia->count,
                    'percentage' => $porcentaje
                ];
            }
        }

        // Último parche publicado
        $ultimoParche = Update::where('type', 'patch')->latest('published_at')->first();

        // Top 3 personajes más usados en builds (Top Todas las Épocas)
        $topPersonajesRaw = Build::select('character_id', DB::raw('count(*) as count'))
            ->groupBy('character_id')
            ->orderBy('count', 'desc')
            ->take(3)
            ->get();

        $topPersonajes = collect();
        foreach ($topPersonajesRaw as $top) {
            $personaje = Item::find($top->character_id);
            if ($personaje) {
                // Attach the count to the object so it can be used if needed
                $personaje->builds_count = $top->count;
                $topPersonajes->push($personaje);
            }
        }

        return view('meta', compact('estrategias', 'notasDeParche', 'tendencias', 'ultimoParche', 'topPersonajes'));
    }

    /**
     * Registra o actualiza el voto de un usuario sobre si una estrategia es meta o no.
     */
    public function votarEstrategiaMeta(Request $request, $id)
    {
        $request->validate([
            'is_meta' => 'required|boolean'
        ]);

        $idUsuario = auth()->id();
        $estrategia = MetaStrategy::findOrFail($id);

        MetaStrategyVote::updateOrCreate(
            ['meta_strategy_id' => $id, 'user_id' => $idUsuario],
            ['is_meta' => $request->is_meta]
        );

        return response()->json([
            'success' => true,
            'confidence' => $estrategia->refresh()->confidence_percentage
        ]);
    }
}
