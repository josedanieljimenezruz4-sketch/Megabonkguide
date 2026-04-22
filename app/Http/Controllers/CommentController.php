<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\TierList;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $tierListId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $tierList = TierList::findOrFail($tierListId);

        Comment::create([
            'user_id' => Auth::id(),
            'tier_list_id' => $tierList->id,
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('community-tierlists.show', $tierList->id)
                         ->with('success', '¡Comentario añadido!');
    }

    public function destroyAdmin($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comentario eliminado.');
    }
}
