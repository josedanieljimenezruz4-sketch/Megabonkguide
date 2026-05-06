<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function mostrarFormularioLogin()
    {
        return view('login');
    }

    /**
     * Muestra el formulario de registro de usuario.
     */
    public function mostrarFormularioRegistro()
    {
        return view('registro');
    }

    /**
     * Muestra la vista de perfil de usuario heredada (sistema de progresión).
     */
    public function mostrarPerfil()
    {
        // El total de inventario del juego ahora se lee directamente y con precisión matemática desde MySQL
        $totalElementos = Item::count();
        $totalElementos = $totalElementos > 0 ? $totalElementos : 1; // Para evitar posible división por cero

        // Calculamos cuántos ha desbloqueado el usuario
        $elementosDesbloqueados = DB::table('user_unlocks')->where('user_id', auth()->id())->count();
        
        // Calculamos el porcentaje
        $porcentaje = round(($elementosDesbloqueados / $totalElementos) * 100);

        return view('perfil', [
            'totalItems' => $totalElementos, 
            'unlockedItems' => $elementosDesbloqueados, 
            'percentage' => $porcentaje
        ]);
    }

    /**
     * Muestra la vista de configuración/ajustes del usuario.
     */
    public function mostrarAjustes()
    {
        return view('cambiar_datos');
    }

    /**
     * Procesa la solicitud de registro de un nuevo usuario.
     */
    public function registrarUsuario(Request $request)
    {
        $datosValidados = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = User::create([
            'username' => $datosValidados['username'],
            'email' => $datosValidados['email'],
            'password' => Hash::make($datosValidados['password']),
        ]);

        Auth::login($usuario);

        return redirect('/');
    }

    /**
     * Procesa la solicitud de inicio de sesión de un usuario.
     */
    public function autenticarUsuario(Request $request)
    {
        $credenciales = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Si el usuario ingresa un correo, buscamos por 'email'. 
        // Si no, buscamos por 'username' (NO por 'name').
        $tipoDeCampo = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$tipoDeCampo => $credenciales['username'], 'password' => $credenciales['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('username');
    }

    /**
     * Cierra la sesión del usuario actual.
     */
    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}