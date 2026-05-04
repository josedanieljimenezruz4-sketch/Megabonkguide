<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function ban(Request $request, $id)
    {
        $request->validate([
            'duration' => 'required|string'
        ]);

        $user = User::findOrFail($id);

        if ($request->duration === 'unban') {
            $user->banned_until = null;
        } elseif ($request->duration === 'permanent') {
            $user->banned_until = Carbon::now()->addYears(100);
        } else {
            $hours = (int) $request->duration;
            $user->banned_until = Carbon::now()->addHours($hours);
        }

        $user->save();

        return redirect()->back()->with('success', 'Estado de baneo actualizado.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}
