<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Crear Cuenta | MEGABONK GUIDE</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" href="iconotlabaho.webp" type="image/x-icon">
</head>

<body>
    <main class="main-content-auth">

        <div class="auth-card">
            <h1 class="auth-title">Crea tu BonkID</h1>
            <p class="auth-subtitle">Únete para guardar builds y participar en la comunidad.</p>

            <form class="auth-form" action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="new-username">Nombre de Usuario</label>
                    <input type="text" id="new-username" name="username" placeholder="Tu nombre de Bonker"
                        value="{{ old('username') }}" required>
                    @error('username')
                        <span class="error-text" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@email.com"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-text" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new-password">Contraseña</label>
                    <input type="password" id="new-password" name="password" placeholder="Mínimo 8 caracteres" required>
                    @error('password')
                        <span class="error-text" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirmar Contraseña</label>
                    <input type="password" id="confirm-password" name="password_confirmation"
                        placeholder="Vuelve a escribir la contraseña" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-auth">Registrarse</button>
                </div>
            </form>

            <div class="oauth-buttons" style="margin-top: 25px; text-align: center;">
                <p style="color: #aaa; font-size: 0.9em; margin-bottom: 15px; position: relative;">
                    <span style="background: #1e1e24; padding: 0 10px; position: relative; z-index: 1;">O regístrate con</span>
                    <span style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px solid #333; z-index: 0;"></span>
                </p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <a href="{{ route('social.redirect', 'google') }}" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; background: #fff; color: #444; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em; border: 1px solid #ddd; transition: background 0.2s;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Google
                    </a>
                    <a href="{{ route('social.redirect', 'discord') }}" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; background: #5865F2; color: white; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        <svg width="18" height="18" viewBox="0 0 127.14 96.36" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a67.58,67.58,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1,105.25,105.25,0,0,0,32.19-16.14c2.64-27.38-4.51-51.11-18.9-72.15ZM42.56,65.36c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C52.36,60.53,48.06,65.36,42.56,65.36Zm42,0c-5.36,0-9.8-4.83-9.8-10.74s4.36-10.74,9.8-10.74c5.5,0,9.89,4.83,9.8,10.74C94.41,60.53,90.1,65.36,84.56,65.36Z"/></svg>
                        Discord
                    </a>
                </div>
            </div>

            <div class="auth-footer-links">
                <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
            </div>
        </div>

    </main>

    <footer class="main-footer">
        <div class="footer-sections">
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                    <li><a href="{{ route('meta') }}">META</a></li>
                    <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                </ul>
            </div>
            <div>
                <h3>Soporte</h3>
                <ul>
                    <li><a href="#">Contáctanos</a></li>
                    <li><a href="{{ route('comunity.suggestions') }}">Sugerencias</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div>
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-copy">&copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.</div>
    </footer>

</body>

</html>