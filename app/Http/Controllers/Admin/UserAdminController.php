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

    /**
     * Alterna el rol de un usuario entre Admin y Usuario normal.
     * Seguridad: El usuario con ID 1 o el email del superadmin está blindado.
     */
    public function toggleRole($id)
    {
        $usuario = User::findOrFail($id);

        // Blindaje del superadmin: ni ID 1 ni el email principal pueden perder admin
        $emailProtegido = 'josedanieljimenezruz4@gmail.com';
        if ($usuario->id == 1 || $usuario->email === $emailProtegido) {
            return redirect()->back()->with('error', '⛔ No puedes modificar el rol del administrador principal del sistema.');
        }

        $usuario->is_admin = !$usuario->is_admin;
        $usuario->save();

        $nuevoRol = $usuario->is_admin ? 'Administrador' : 'Usuario';
        return redirect()->back()->with('success', "Rol de {$usuario->username} cambiado a {$nuevoRol}.");
    }
}
