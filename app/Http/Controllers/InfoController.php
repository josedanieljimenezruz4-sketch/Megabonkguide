<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Update;

class InfoController extends Controller
{
    public function general()
    {
        return view('info_general');
    }

    public function news()
    {
        $updates = Update::orderBy('published_at', 'desc')->get();
        return view('novedades', compact('updates'));
    }
}
