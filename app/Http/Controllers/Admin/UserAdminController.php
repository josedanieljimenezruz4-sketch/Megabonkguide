<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserAdminController extends Controller
{
    /**
     * Muestra la lista paginada de usuarios registrados.
     */
    public function mostrarUsuarios()
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', ['users' => $usuarios]);
    }

    /**
     * Banea temporalmente o permanentemente a un usuario, o retira el baneo.
     */
    public function gestionarBaneo(Request $request, $id)
    {
        $request->validate([
            'duration' => 'required|string'
        ]);

        $usuario = User::findOrFail($id);

        if ($request->duration === 'unban') {
            $usuario->banned_until = null;
        } elseif ($request->duration === 'permanent') {
            $usuario->banned_until = Carbon::now()->addYears(100);
        } else {
            $horas = (int) $request->duration;
            $usuario->banned_until = Carbon::now()->addHours($horas);
        }

        $usuario->save();

        return redirect()->back()->with('success', 'Estado de baneo actualizado.');
    }

    /**
     * Elimina permanentemente una cuenta de usuario.
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}
