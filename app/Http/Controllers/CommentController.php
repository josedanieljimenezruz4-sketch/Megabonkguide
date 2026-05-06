<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\TierList;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Valida y guarda un nuevo comentario asociado a una Tier List específica.
     */
    public function guardarComentario(Request $request, $tierListId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'depth' => 'nullable|integer',
        ]);

        $tierList = TierList::findOrFail($tierListId);

        $comentario = Comment::create([
            'user_id' => Auth::id(),
            'tier_list_id' => $tierList->id,
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $comentario->load('user', 'replies');
            $html = view('community.partials.comment', [
                'comment' => $comentario,
                'depth' => $request->input('depth', 0),
                'submitUrl' => route('community-tierlists.comment', $tierList->id)
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'parent_id' => $comentario->parent_id
            ]);
        }

        return redirect()->route('community-tierlists.show', $tierList->id)
                         ->with('success', '¡Comentario añadido!');
    }

    /**
     * Acción de administrador para eliminar un comentario.
     */
    public function eliminarComentarioAdmin($id)
    {
        $comentario = Comment::findOrFail($id);
        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado.');
    }
}
