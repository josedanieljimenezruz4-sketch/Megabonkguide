<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckBanned
{
    /**
     * Intercepta usuarios baneados y los redirige a la pantalla de prisión.
     * Si el baneo ya ha expirado, permite el acceso con normalidad.
     * La ruta /banned queda excluida para evitar bucles infinitos de redirección.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $usuario = Auth::user();

            // Comprobar si el usuario tiene un baneo activo (fecha futura)
            if ($usuario->banned_until && Carbon::parse($usuario->banned_until)->isFuture()) {
                // Excluir la ruta de prisión y logout para evitar bucles infinitos
                if (!$request->is('banned') && !$request->is('logout')) {
                    return redirect()->route('banned');
                }
            }
        }

        return $next($request);
    }
}
