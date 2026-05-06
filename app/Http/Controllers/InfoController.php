<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Update;

class InfoController extends Controller
{
    /**
     * Muestra la vista de información general (legacy, ahora se maneja vía WikiController).
     */
    public function mostrarInfoGeneral()
    {
        return view('info_general');
    }

    /**
     * Muestra la lista de las últimas novedades y parches del juego.
     */
    public function mostrarNovedades()
    {
        $actualizaciones = Update::orderBy('published_at', 'desc')->get();
        return view('novedades', ['updates' => $actualizaciones]);
    }
}
