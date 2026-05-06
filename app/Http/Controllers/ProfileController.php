<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Item;
use App\Models\Build;
use App\Models\TierList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil privado del usuario autenticado.
     */
    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        
        // Datos para la barra de progreso de desbloqueos
        $totalElementos = Item::count();
        $totalElementos = $totalElementos > 0 ? $totalElementos : 1;
        $desbloqueosUsuario = DB::table('user_unlocks')->where('user_id', $usuario->id)->count();
        $porcentaje = round(($desbloqueosUsuario / $totalElementos) * 100);
        $faltantes = $totalElementos - $desbloqueosUsuario;

        // Historial de actividad del usuario (Builds y Tier Lists)
        $buildsDelUsuario = Build::where('user_id', $usuario->id)->orderBy('created_at', 'desc')->get();
        $tierListsDelUsuario = TierList::where('user_id', $usuario->id)->orderBy('created_at', 'desc')->get();

        // Mantenemos los nombres de variables en inglés en compact para no romper profile.blade.php
        return view('profile', [
            'user' => $usuario,
            'totalItems' => $totalElementos,
            'unlocksCount' => $desbloqueosUsuario,
            'progreso' => $porcentaje,
            'faltantes' => $faltantes,
            'builds' => $buildsDelUsuario,
            'tierLists' => $tierListsDelUsuario
        ]);
    }

    /**
     * Actualiza la imagen de avatar del usuario autenticado.
     */
    public function actualizarAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $usuario = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatarSubido = $request->file('avatar');
            $nombreArchivo = time() . '_' . $usuario->id . '.' . $avatarSubido->getClientOriginalExtension();
            
            // Eliminar el avatar antiguo si existe y es local
            if ($usuario->avatar && !str_starts_with($usuario->avatar, 'http')) {
                Storage::disk('public')->delete('avatars/' . $usuario->avatar);
            }

            // Crear el directorio si no existe
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }

            // Usar Intervention Image para redimensionar a 300x300 y guardar
            $rutaRuta = storage_path('app/public/avatars/' . $nombreArchivo);
            Image::make($avatarSubido)->fit(300, 300)->save($rutaRuta);

            $usuario->avatar = $nombreArchivo;
            $usuario->save();

            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/avatars/' . $nombreArchivo),
                'message' => 'Avatar actualizado correctamente.'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No se ha subido ninguna imagen.']);
    }

    /**
     * Muestra el perfil público de un usuario especificado por su ID.
     */
    public function mostrarPerfilPublico($id)
    {
        $usuarioPublico = User::findOrFail($id);
        
        // Si el usuario está viendo su propio perfil, lo redirigimos al perfil privado
        if (auth()->check() && auth()->id() == $usuarioPublico->id) {
            return redirect()->route('profile');
        }

        // Datos para la barra de progreso
        $totalElementos = Item::count();
        $totalElementos = $totalElementos > 0 ? $totalElementos : 1;
        $desbloqueosUsuario = DB::table('user_unlocks')->where('user_id', $usuarioPublico->id)->count();
        $porcentaje = round(($desbloqueosUsuario / $totalElementos) * 100);
        $faltantes = $totalElementos - $desbloqueosUsuario;

        // Historial de actividad del usuario
        $buildsDelUsuario = Build::where('user_id', $usuarioPublico->id)->orderBy('created_at', 'desc')->get();
        $tierListsDelUsuario = TierList::where('user_id', $usuarioPublico->id)->orderBy('created_at', 'desc')->get();

        return view('profile', [
            'user' => $usuarioPublico,
            'totalItems' => $totalElementos,
            'unlocksCount' => $desbloqueosUsuario,
            'progreso' => $porcentaje,
            'faltantes' => $faltantes,
            'builds' => $buildsDelUsuario,
            'tierLists' => $tierListsDelUsuario
        ]);
    }
}
