<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnlockController extends Controller
{
    public function index()
    {
        return view('unlocks');
    }

    public function weapons()
    {
        return view('armas');
    }

    public function tomes()
    {
        return view('tomos');
    }

    public function items()
    {
        return view('items');
    }

    public function characters()
    {
        return view('personajes');
    }
}
