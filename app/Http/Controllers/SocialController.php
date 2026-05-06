<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    /**
     * Redirige al usuario al proveedor de OAuth seleccionado.
     */
    public function redirigirAlProveedor($proveedor)
    {
        return Socialite::driver($proveedor)->redirect();
    }

    /**
     * Maneja el callback de autenticación tras la respuesta del proveedor.
     */
    public function manejarCallbackDelProveedor($proveedor)
    {
        try {
            $usuarioSocial = Socialite::driver($proveedor)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['oauth' => 'Error en la autenticación con ' . ucfirst($proveedor)]);
        }

        // Buscar usuario por email (o por ID si ya está vinculado y no dio email)
        if ($usuarioSocial->getEmail()) {
            $usuario = User::where('email', $usuarioSocial->getEmail())->first();
        } else {
            // Algunos proveedores podrían no devolver email. Buscamos por el ID del proveedor.
            $columna = $proveedor . '_id';
            $usuario = User::where($columna, $usuarioSocial->getId())->first();
        }

        if ($usuario) {
            // Actualizar IDs y avatar si existen
            if ($proveedor == 'google' && !$usuario->google_id) {
                $usuario->google_id = $usuarioSocial->getId();
            }
            if ($proveedor == 'discord' && !$usuario->discord_id) {
                $usuario->discord_id = $usuarioSocial->getId();
            }
            // Actualizar avatar si el usuario no tiene o si queremos mantenerlo actualizado
            if ($usuarioSocial->getAvatar()) {
                $usuario->avatar = $usuarioSocial->getAvatar();
            }
            $usuario->save();
        } else {
            // Crear nuevo usuario
            $email = $usuarioSocial->getEmail() ?? $usuarioSocial->getId() . '@' . $proveedor . '.com';

            $usuario = User::create([
                'username' => $usuarioSocial->getName() ?? $usuarioSocial->getNickname() ?? 'Usuario_' . Str::random(5),
                'email' => $email,
                'password' => null, // El password será nulo
                'google_id' => $proveedor == 'google' ? $usuarioSocial->getId() : null,
                'discord_id' => $proveedor == 'discord' ? $usuarioSocial->getId() : null,
                'avatar' => $usuarioSocial->getAvatar(),
            ]);
        }

        Auth::login($usuario, true); // true para "remember me"

        return redirect('/')->with('success', 'Bienvenido, ' . $usuario->username);
    }
}
