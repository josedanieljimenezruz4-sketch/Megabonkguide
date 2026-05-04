<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suggestion;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function destroy($id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->delete();

        return redirect()->route('admin.suggestions.index')->with('success', 'Sugerencia eliminada correctamente.');
    }

    public function markRead($id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->is_read = true;
        $suggestion->save();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,completed'
        ]);

        $suggestion = Suggestion::findOrFail($id);
        $suggestion->status = $request->status;
        $suggestion->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $suggestion->status]);
        }

        return redirect()->route('admin.suggestions.index')->with('success', 'Estado actualizado correctamente.');
    }
}
