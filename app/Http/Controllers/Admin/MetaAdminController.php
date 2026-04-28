<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaStrategy;
use App\Models\PatchNote;

class MetaAdminController extends Controller
{
    public function index()
    {
        $strategies = MetaStrategy::orderBy('created_at', 'desc')->get();
        $patchNotes = PatchNote::orderBy('created_at', 'desc')->get();

        return view('admin.meta', compact('strategies', 'patchNotes'));
    }

    public function storeStrategy(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'build_type' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        MetaStrategy::create([
            'title' => $request->title,
            'description' => $request->description,
            'build_type' => $request->build_type,
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Estrategia creada exitosamente.');
    }

    public function destroyStrategy($id)
    {
        MetaStrategy::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Estrategia eliminada.');
    }

    public function storePatchNote(Request $request)
    {
        $request->validate([
            'version' => 'nullable|string|max:50',
            'change_type' => 'required|in:buff,nerf,new',
            'description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        PatchNote::create([
            'version' => $request->version,
            'change_type' => $request->change_type,
            'description' => $request->description,
            'is_active' => $request->input('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Nota del parche añadida.');
    }

    public function destroyPatchNote($id)
    {
        PatchNote::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Nota eliminada.');
    }
}
