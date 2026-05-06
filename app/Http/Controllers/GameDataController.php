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

        // Obtener los votos de rango para los ítems pendientes
        $idsPendientes = $elementosPendientes->pluck('id')->toArray();
        $votosDeRango = DB::table('user_rank_votes')
            ->select('item_id', 'suggested_rank', DB::raw('count(*) as total'))
            ->whereIn('item_id', $idsPendientes)
            ->groupBy('item_id', 'suggested_rank')
            ->get();
            
        // Determinar el rango más votado para cada ítem pendiente
        $rangosMasVotados = [];
        $votosAgrupados = collect($votosDeRango)->groupBy('item_id');
        foreach ($votosAgrupados as $itemId => $votos) {
            $masVotado = $votos->sortByDesc('total')->first();
            $rangosMasVotados[$itemId] = $masVotado->suggested_rank;
        }

        // Obtener las últimas Tier Lists de la comunidad para mostrar en el sidebar
        $tierListsRecientes = TierList::with(['user', 'rows.item'])->latest()->take(5)->get();

        return view('tierlist', [
            'itemsByRank' => $elementosPorRango,
            'pendingItems' => $elementosPendientes,
            'mostVotedRanks' => $rangosMasVotados,
            'category' => $categoria,
            'sort' => $ordenar,
            'recentCommunityTierLists' => $tierListsRecientes,
        ]);
    }

    /**
     * Registra un voto de popularidad para un ítem. Solo permite un voto por usuario.
     */
    public function votarElemento(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $idUsuario = auth()->id();
        
        // Comprobar si el usuario ya ha votado este ítem
        $yaVotado = DB::table('item_user_votes')
            ->where('user_id', $idUsuario)
            ->where('item_id', $id)
            ->exists();
            
        if ($yaVotado) {
            return response()->json([
                'success' => false,
                'message' => '¡Solo puedes votar una vez por este item!'
            ]);
        }
        
        // Registrar el voto del usuario
        DB::table('item_user_votes')->insert([
            'user_id' => $idUsuario,
            'item_id' => $id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $item->increment('votes');

        return response()->json([
            'success' => true,
            'votes' => $item->votes
        ]);
    }

    /**
     * Registra o actualiza el voto de rango sugerido por un usuario para un ítem pendiente.
     */
    public function votarRangoElemento(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $request->validate([
            'rank' => 'required|in:S,A,B,C,D,E,F'
        ]);
        
        $idUsuario = auth()->id();
        $rango = $request->input('rank');
        
        // Crear o actualizar el voto del usuario para este ítem
        DB::table('user_rank_votes')->updateOrInsert(
            ['user_id' => $idUsuario, 'item_id' => $id],
            ['suggested_rank' => $rango, 'updated_at' => now()]
        );
        
        // Determinar el rango más votado actualmente
        $masVotado = DB::table('user_rank_votes')
            ->select('suggested_rank', DB::raw('count(*) as total'))
            ->where('item_id', $id)
            ->groupBy('suggested_rank')
            ->orderBy('total', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'most_voted_rank' => $masVotado ? $masVotado->suggested_rank : $rango
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

        // Top 3 personajes más usados en builds
        $topPersonajes = Item::where('type', 'personaje')
            ->withCount('characterBuilds')
            ->orderBy('character_builds_count', 'desc')
            ->take(3)
            ->get();

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
