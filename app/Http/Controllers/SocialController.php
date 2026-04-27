<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['oauth' => 'Error en la autenticación con ' . ucfirst($provider)]);
        }

        // Buscar usuario por email (o por ID si ya está vinculado y no dio email)
        if ($socialUser->getEmail()) {
            $user = User::where('email', $socialUser->getEmail())->first();
        } else {
            // Algunos proveedores podrían no devolver email. Buscamos por el ID del proveedor.
            $column = $provider . '_id';
            $user = User::where($column, $socialUser->getId())->first();
        }

        if ($user) {
            // Actualizar IDs y avatar si existen
            if ($provider == 'google' && !$user->google_id) {
                $user->google_id = $socialUser->getId();
            }
            if ($provider == 'discord' && !$user->discord_id) {
                $user->discord_id = $socialUser->getId();
            }
            // Actualizar avatar si el usuario no tiene o si queremos mantenerlo actualizado
            if ($socialUser->getAvatar()) {
                $user->avatar = $socialUser->getAvatar();
            }
            $user->save();
        } else {
            // Crear nuevo usuario
            $email = $socialUser->getEmail() ?? $socialUser->getId() . '@' . $provider . '.com';

            $user = User::create([
                'username' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuario_' . Str::random(5),
                'email' => $email,
                'password' => null, // El password será nulo
                'google_id' => $provider == 'google' ? $socialUser->getId() : null,
                'discord_id' => $provider == 'discord' ? $socialUser->getId() : null,
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true); // true para "remember me"

        return redirect('/')->with('success', 'Bienvenido, ' . $user->username);
    }
}
