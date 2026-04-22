<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Acceso | AM+' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
        <script src="{{ asset('dashboard.js') }}" defer></script>
    @endif
</head>
<body>
    @if (session('success'))
        <div
            id="app-flash"
            data-flash-type="success"
            data-flash-title="Operacion completada"
            data-flash-message="{{ session('success') }}"
            hidden
        ></div>
    @endif

    <main class="auth-shell">
        <section class="auth-layout">
            <article class="auth-aside">
                <div class="auth-brand auth-brand-large">
                    <span class="brand-mark">AM</span>
                    <div>
                        <p class="brand-title">AM+</p>
                        <p class="brand-subtitle">Plataforma administrativa</p>
                    </div>
                </div>

                <div class="auth-aside-copy">
                    <p class="panel-kicker">Control centralizado</p>
                    <h1>Gestiona P. Morales, P. Fisicas, usuarios y archivos desde un solo panel.</h1>
                    <p class="helper-text">
                        Accede con tu cuenta para administrar operaciones, documentos fiscales, P. Fisicas y usuarios del sistema.
                    </p>
                </div>

                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <strong>P. Morales</strong>
                        <span>Alta, seguimiento y documentos operativos.</span>
                    </div>
                    <div class="auth-feature-item">
                        <strong>Usuarios</strong>
                        <span>Control de accesos internos y mantenimiento de cuentas.</span>
                    </div>
                    <div class="auth-feature-item">
                        <strong>Resguardo</strong>
                        <span>Consulta rapida de SAT, FIEL y P. Fisicas desde un mismo lugar.</span>
                    </div>
                </div>

                <div class="auth-signature">
                    <span>Programado por HopJet y EDW</span>
                </div>
            </article>

            <section class="auth-card">
                <div class="auth-copy">
                    <p class="panel-kicker">Seguridad</p>
                    <h2>Iniciar sesion</h2>
                    <p class="helper-text">
                        Ingresa tus credenciales para continuar al panel principal.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                    @csrf

                    <div class="field-group">
                        <label for="email">Correo electronico</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="field-group">
                        <label for="password">Contrasena</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <label class="checkbox-row" for="remember">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span>Recordarme en este equipo</span>
                    </label>

                    <button type="submit" class="button button-primary auth-submit">Entrar al sistema</button>
                </form>

                <p class="auth-card-signature">Desarrollo y programacion por HopJet y EDW</p>
            </section>
        </section>
    </main>

    <div class="swal-toast" id="app-toast" hidden>
        <div class="swal-toast-icon" id="app-toast-icon">OK</div>
        <div class="swal-toast-content">
            <strong id="app-toast-title">Operacion completada</strong>
            <span id="app-toast-message"></span>
        </div>
        <button type="button" class="swal-toast-close" id="app-toast-close" aria-label="Cerrar">x</button>
    </div>
</body>
</html>
