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
     * Actualiza los datos de perfil del usuario.
     */
    public function actualizarAjustes(Request $request)
    {
        $usuario = Auth::user();

        $reglas = [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/',
                'not_in:admin,staff,megabonk',
                'unique:users,username,' . $usuario->id,
            ],
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'new_password' => 'nullable|string|min:8|confirmed',
        ];

        $mensajes = [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'username.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'username.max' => 'El nombre no debe superar los 20 caracteres.',
            'username.regex' => 'El nombre solo puede contener letras, números y guiones bajos (sin espacios).',
            'username.not_in' => 'Este nombre de usuario está reservado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'La imagen no debe pesar más de 2MB.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];

        $datosValidados = $request->validate($reglas, $mensajes);

        $usuario->username = $datosValidados['username'];
        $usuario->email = $datosValidados['email'];

        if ($request->filled('new_password')) {
            $usuario->password = Hash::make($datosValidados['new_password']);
        }

        if ($request->hasFile('avatar')) {
            $avatarSubido = $request->file('avatar');
            $extension = strtolower($avatarSubido->getClientOriginalExtension());
            $nombreArchivo = time() . '_' . $usuario->id . '.' . $extension;
            
            // Eliminar el avatar antiguo si existe y es local
            if ($usuario->avatar && !str_starts_with($usuario->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('avatars/' . $usuario->avatar);
            }

            // Crear el directorio si no existe
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('avatars')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('avatars');
            }

            // Evitar redimensionar WebP con GD ya que causa Fatal Error 'gd-webp cannot allocate temporary buffer' en Windows
            if ($extension === 'webp') {
                $avatarSubido->storeAs('avatars', $nombreArchivo, 'public');
            } else {
                try {
                    $rutaRuta = storage_path('app/public/avatars/' . $nombreArchivo);
                    \Intervention\Image\Facades\Image::make($avatarSubido)->fit(300, 300)->save($rutaRuta);
                } catch (\Throwable $e) {
                    // Fallback de seguridad si falla GD
                    $avatarSubido->storeAs('avatars', $nombreArchivo, 'public');
                }
            }

            $usuario->avatar = $nombreArchivo;
        }

        $usuario->save();

        // Flujo correcto de Laravel: Formulario (POST) -> Controlador -> Redirección (GET) -> Vista
        return redirect()->route('profile.settings')->with('success', 'Datos actualizados correctamente');
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