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
    /**
     * Muestra el perfil privado del usuario autenticado.
     */
    public function mostrarPerfil()
    {
        $usuario = Auth::user();

        // 1. Total de ítems reales hoy en la base de datos (13)
        $totalElementos = Item::count();

        // 2. Conteo blindado: Solo contamos los desbloqueos cuyos item_id EXISTAN en la tabla items
        $desbloqueosUsuario = DB::table('user_unlocks')
            ->where('user_id', $usuario->id)
            ->whereIn('item_id', Item::pluck('id')) // <-- LA LLAVE MAESTRA: Filtra solo IDs reales actuales
            ->count(DB::raw('DISTINCT item_id'));

        // 3. Cálculo del progreso real matemático
        $porcentaje = $totalElementos > 0 ? round(($desbloqueosUsuario / $totalElementos) * 100) : 0;

        // 4. Cantidad de ítems faltantes
        $faltantes = max(0, $totalElementos - $desbloqueosUsuario);

        // 5. Historial de actividad del usuario (Builds y Tier Lists)
        $buildsDelUsuario = Build::where('user_id', $usuario->id)->orderBy('created_at', 'desc')->get();
        $tierListsDelUsuario = TierList::where('user_id', $usuario->id)->orderBy('created_at', 'desc')->get();

        // 6. Retorno unificado mapeando los nombres exactos que espera profile.blade.php
        return view('profile', [
            'user' => $usuario,
            'totalItems' => $totalElementos,
            'unlocksCount' => $desbloqueosUsuario,
            'progreso' => $porcentaje, // Esta es la variable clave con el porcentaje real
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
        $desbloqueosUsuario = DB::table('user_unlocks')->where('user_id', $usuarioPublico->id)->count(DB::raw('DISTINCT item_id'));

        $porcentaje = $totalElementos > 0 ? round(($desbloqueosUsuario / $totalElementos) * 100) : 0;
        $faltantes = max(0, $totalElementos - $desbloqueosUsuario);

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
