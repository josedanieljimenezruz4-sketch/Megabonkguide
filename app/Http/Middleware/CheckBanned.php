<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckBanned
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->banned_until && Carbon::parse($user->banned_until)->isFuture()) {
                $bannedUntil = Carbon::parse($user->banned_until);
                $message = 'Tu cuenta estÃ¡ suspendida hasta el ' . $bannedUntil->format('d/m/Y H:i') . '.';
                if ($bannedUntil->diffInYears(Carbon::now()) > 50) {
                    $message = 'Tu cuenta ha sido suspendida permanentemente.';
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', $message);
            }
        }

        return $next($request);
    }
}
