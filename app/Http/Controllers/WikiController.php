<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameInfo;
use App\Models\Faq;

class WikiController extends Controller
{
    /**
     * Muestra la página principal de la Wiki (Información General y FAQs) con opciones de búsqueda.
     */
    public function mostrarWiki(Request $request)
    {
        $terminoBusqueda = $request->input('search');

        if ($terminoBusqueda) {
            $informacion = GameInfo::where('title', 'LIKE', "%{$terminoBusqueda}%")
                            ->orWhere('content', 'LIKE', "%{$terminoBusqueda}%")
                            ->get();

            $preguntasFrecuentes = Faq::where('title', 'LIKE', "%{$terminoBusqueda}%")
                            ->orWhere('content', 'LIKE', "%{$terminoBusqueda}%")
                            ->get();
        } else {
            $informacion = GameInfo::all();
            $preguntasFrecuentes = Faq::all();
        }

        return view('info_general', [
            'infos' => $informacion,
            'faqs' => $preguntasFrecuentes,
            'search' => $terminoBusqueda
        ]);
    }
}
