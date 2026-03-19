<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalUnlocks = DB::table('user_unlocks')->count();
        $totalItems = \App\Models\Item::count();
        $totalAdmins = User::where('is_admin', true)->count();

        // Obtenemos los últimos 10 usuarios para mostrarlos en la tabla
        $latestUsers = User::latest()->take(10)->get();

        return view('admin', compact('totalUsers', 'totalAdmins', 'totalUnlocks', 'totalItems', 'latestUsers'));
    }
}
