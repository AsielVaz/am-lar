<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AM+' }}</title>
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

    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="sidebar-main">
                <div class="brand">
                    <span class="brand-mark">AM</span>
                    <div>
                        <p class="brand-title">AM+</p>
                        <p class="brand-subtitle">Panel principal</p>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        Consulta
                    </a>
                    @if (auth()->user()->isAdministrador() || auth()->user()->isCapturista())
                        <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.index', 'empresas.show', 'empresas.edit') ? 'is-active' : '' }}">
                            P. Morales
                        </a>
                        <a href="{{ route('socios.index') }}" class="nav-link {{ request()->routeIs('socios.*') ? 'is-active' : '' }}">
                            P. Fisicas
                        </a>
                    @endif
                    @if (auth()->user()->isAdministrador())
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}">
                            Usuarios
                        </a>
                        <a href="{{ route('empresas.create') }}" class="nav-link {{ request()->routeIs('empresas.create') ? 'is-active' : '' }}">
                            Nueva P. Moral
                        </a>
                    @endif
                </nav>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <span class="user-chip-label">Sesion activa</span>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span class="sidebar-user-email">{{ \App\Models\User::roles()[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}</span>
                    <span class="sidebar-user-email">{{ auth()->user()->email }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" class="sidebar-logout-button">Cerrar sesion</button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-heading-group">
                    <div>
                        <p class="eyebrow">Sistema de gestion</p>
                        <h1>{{ $heading ?? 'Dashboard' }}</h1>
                    </div>

                    @hasSection('topbar_leading_actions')
                        <div class="topbar-leading-actions">
                            @yield('topbar_leading_actions')
                        </div>
                    @endif
                </div>

                @hasSection('topbar_actions')
                    <div class="topbar-actions">
                        @yield('topbar_actions')
                    </div>
                @endif
            </header>

            @yield('content')

            <footer class="app-footer">
                <span>Sistema AM+</span>
                <span>Programado por HopJet y EDW</span>
            </footer>
        </main>
    </div>

    <dialog class="data-modal app-confirm-modal" id="app-confirm-dialog">
        <div class="modal-card confirm-card">
            <div class="confirm-icon confirm-icon-warning">!</div>
            <div class="confirm-content">
                <h3 id="app-confirm-title">Confirmar accion</h3>
                <p id="app-confirm-message">Esta accion requiere confirmacion.</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="button button-secondary" id="app-confirm-cancel">Cancelar</button>
                <button type="button" class="button button-danger" id="app-confirm-accept">Continuar</button>
            </div>
        </div>
    </dialog>

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
