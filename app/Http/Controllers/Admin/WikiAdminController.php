<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameInfo;
use App\Models\Faq;

class WikiAdminController extends Controller
{
    public function index()
    {
        $infos = GameInfo::all();
        $faqs = Faq::all();

        return view('admin.wiki.index', compact('infos', 'faqs'));
    }

    // --- GAME INFO ---
    public function storeGameInfo(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        GameInfo::create($validated);
        return redirect()->route('admin.wiki.index')->with('success', 'Información añadida.');
    }

    public function updateGameInfo(Request $request, $id)
    {
        $info = GameInfo::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $info->update($validated);
        return redirect()->route('admin.wiki.index')->with('success', 'Información actualizada.');
    }

    public function destroyGameInfo($id)
    {
        GameInfo::findOrFail($id)->delete();
        return redirect()->route('admin.wiki.index')->with('success', 'Información eliminada.');
    }

    // --- FAQS ---
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        Faq::create($validated);
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ añadida.');
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $faq->update($validated);
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ actualizada.');
    }

    public function destroyFaq($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ eliminada.');
    }
}
