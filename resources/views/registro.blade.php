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