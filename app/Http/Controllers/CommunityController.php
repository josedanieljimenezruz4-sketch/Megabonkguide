<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        // Consulta simplificada para depurar
        $posts = CommunityPost::with('user')->latest()->paginate(10);
        
        $filter = 'recent'; // Añadido para que no falle la vista al quitar el dd()
        return view('comunity', compact('posts', 'filter'));
    }

    public function show($id)
    {
        $post = CommunityPost::with(['user', 'comments' => function($query) {
            $query->whereNull('parent_id')->with(['user', 'replies']);
        }])->findOrFail($id);
        
        return view('community.show', compact('post'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:build,meta,question,meme',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community_posts', 'public');
        }

        CommunityPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('comunity.index')->with('success', 'Publicación creada exitosamente.');
    }

    public function like($id, Request $request)
    {
        $post = CommunityPost::findOrFail($id);
        $user = Auth::user();

        $isLiked = false;
        if ($post->isLikedBy($user)) {
            $post->likes()->detach($user->id);
            $post->decrement('likes_count');
        } else {
            $post->likes()->attach($user->id);
            $post->increment('likes_count');
            $isLiked = true;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes_count,
                'is_liked' => $isLiked
            ]);
        }

        return redirect()->back();
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'depth' => 'nullable|integer',
        ]);

        $post = CommunityPost::findOrFail($id);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'community_post_id' => $post->id,
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $comment->load('user', 'replies');
            $html = view('community.partials.comment', [
                'comment' => $comment,
                'depth' => $request->input('depth', 0),
                'submitUrl' => route('comunity.comment', $post->id),
                'post' => $post
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'parent_id' => $comment->parent_id
            ]);
        }

        return redirect()->route('comunity.show', $post->id)->with('success', 'Comentario añadido.');
    }

    public function suggestions()
    {
        return view('sugerencias');
    }

    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        \App\Models\Suggestion::create([
            'user_id' => Auth::id(), // Puede ser null si no está logueado
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', '¡Gracias por tu sugerencia! Ha sido enviada a los administradores.');
    }
}
