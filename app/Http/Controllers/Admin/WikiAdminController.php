<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameInfo;
use App\Models\Faq;

class WikiAdminController extends Controller
{
    /**
     * Muestra el panel de administración de la Wiki (Información y FAQs).
     */
    public function mostrarPanelWiki()
    {
        $informacion = GameInfo::all();
        $preguntasFrecuentes = Faq::all();

        return view('admin.wiki.index', [
            'informacion' => $informacion,
            'preguntasFrecuentes' => $preguntasFrecuentes
        ]);
    }

    // --- INFORMACIÓN DEL JUEGO ---

    /**
     * Guarda una nueva entrada de información del juego.
     */
    public function guardarInformacionJuego(Request $request)
    {
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        GameInfo::create($datosValidados);
        return redirect()->route('admin.wiki.index')->with('success', 'Información añadida.');
    }

    /**
     * Actualiza una entrada existente de información del juego.
     */
    public function actualizarInformacionJuego(Request $request, $id)
    {
        $info = GameInfo::findOrFail($id);
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $info->update($datosValidados);
        return redirect()->route('admin.wiki.index')->with('success', 'Información actualizada.');
    }

    /**
     * Elimina permanentemente una entrada de información del juego.
     */
    public function eliminarInformacionJuego($id)
    {
        GameInfo::findOrFail($id)->delete();
        return redirect()->route('admin.wiki.index')->with('success', 'Información eliminada.');
    }

    // --- FAQs ---

    /**
     * Guarda una nueva Pregunta Frecuente (FAQ).
     */
    public function guardarPreguntaFrecuente(Request $request)
    {
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        Faq::create($datosValidados);
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ añadida.');
    }

    /**
     * Actualiza una Pregunta Frecuente (FAQ) existente.
     */
    public function actualizarPreguntaFrecuente(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $datosValidados = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $faq->update($datosValidados);
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ actualizada.');
    }

    /**
     * Elimina permanentemente una Pregunta Frecuente (FAQ).
     */
    public function eliminarPreguntaFrecuente($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.wiki.index')->with('success', 'FAQ eliminada.');
    }
}
