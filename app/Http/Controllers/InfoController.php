<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function general()
    {
        return view('info_general');
    }

    public function news()
    {
        return view('novedades');
    }
}
