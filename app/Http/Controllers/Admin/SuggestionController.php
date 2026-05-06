<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suggestion;

class SuggestionController extends Controller
{
    /**
     * Muestra la lista paginada de sugerencias enviadas por la comunidad.
     */
    public function mostrarSugerencias()
    {
        $sugerencias = Suggestion::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.suggestions.index', ['suggestions' => $sugerencias]);
    }

    /**
     * Elimina permanentemente una sugerencia de la base de datos.
     */
    public function eliminarSugerencia($id)
    {
        $sugerencia = Suggestion::findOrFail($id);
        $sugerencia->delete();

        return redirect()->route('admin.suggestions.index')->with('success', 'Sugerencia eliminada correctamente.');
    }

    /**
     * Marca una sugerencia como leída mediante petición AJAX.
     */
    public function marcarComoLeida($id)
    {
        $sugerencia = Suggestion::findOrFail($id);
        $sugerencia->is_read = true;
        $sugerencia->save();

        return response()->json(['success' => true]);
    }

    /**
     * Actualiza el estado de progreso de una sugerencia (pendiente, en revisión, completada).
     */
    public function actualizarEstadoSugerencia(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,completed'
        ]);

        $sugerencia = Suggestion::findOrFail($id);
        $sugerencia->status = $request->status;
        $sugerencia->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $sugerencia->status]);
        }

        return redirect()->route('admin.suggestions.index')->with('success', 'Estado actualizado correctamente.');
    }
}
