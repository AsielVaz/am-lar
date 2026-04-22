@extends('layouts.app', [
    'title' => 'Consulta | AM+',
    'heading' => 'Consulta',
])

@section('topbar_actions')
    <div class="topbar-actions-stack">
        <div class="topbar-filter-group" aria-label="Filtrar por tipo de persona">
            <a href="{{ route('dashboard', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => $estatus !== '' ? $estatus : null])) }}" class="topbar-filter-button is-active">
                P. Morales
            </a>
            <a href="{{ route('socios.index') }}" class="topbar-filter-button">
                P. Fisicas
            </a>
        </div>

        <div class="topbar-filter-group" aria-label="Filtrar por estatus">
            <a href="{{ route('dashboard', array_filter(['search' => $search !== '' ? $search : null])) }}" class="topbar-filter-button {{ $estatus === '' ? 'is-active' : '' }}">
                Todas
            </a>
            <a href="{{ route('dashboard', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'activa'])) }}" class="topbar-filter-button {{ $estatus === 'activa' ? 'is-active' : '' }}">
                Activas
            </a>
            <a href="{{ route('dashboard', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'inactiva'])) }}" class="topbar-filter-button {{ $estatus === 'inactiva' ? 'is-active' : '' }}">
                Inactivas
            </a>
            <a href="{{ route('dashboard', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'inerte'])) }}" class="topbar-filter-button {{ $estatus === 'inerte' ? 'is-active' : '' }}">
                Inertes
            </a>
        </div>
    </div>
@endsection

@section('content')
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-label">P. Morales registradas</span>
            <strong class="stat-value">{{ $estadisticas['total'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Activas</span>
            <strong class="stat-value">{{ $estadisticas['activas'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Inactivas</span>
            <strong class="stat-value">{{ $estadisticas['inactivas'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Inertes</span>
            <strong class="stat-value">{{ $estadisticas['inertes'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Prioridad alta</span>
            <strong class="stat-value">{{ $estadisticas['altaPrioridad'] }}</strong>
        </article>
    </section>

    <section class="panel panel-elevated">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">Consulta</p>
                <h2>Directorio de P. Morales</h2>
            </div>
            @if (auth()->user()->isAdministrador())
                <a href="{{ route('empresas.create') }}" class="button button-secondary">Nueva P. Moral</a>
            @endif
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="panel-search-form">
            <div class="panel-search-group">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Buscar P. Morales por nombre, RFC, direccion o correo"
                    class="panel-search-input"
                >
                @if ($estatus !== '')
                    <input type="hidden" name="estatus" value="{{ $estatus }}">
                @endif
                @if ($search !== '')
                    <a href="{{ route('dashboard', $estatus !== '' ? ['estatus' => $estatus] : []) }}" class="button button-secondary">Limpiar</a>
                @endif
                <button type="submit" class="button button-primary">Buscar</button>
            </div>
        </form>

        <div class="directory-summary">
            <span>{{ $empresasTotalFiltradas }} P. Morales encontradas</span>
        </div>

        @forelse ($empresasAgrupadas as $initial => $group)
            <section class="alpha-group">
                <div class="alpha-group-header">
                    <span class="alpha-group-mark">{{ $initial }}</span>
                </div>

                <div class="empresa-card-grid">
                    @foreach ($group as $empresa)
                        <a
                            href="{{ route('empresas.show', $empresa) }}"
                            class="empresa-directory-card {{ $empresa->logo ? 'has-logo' : 'no-logo' }}"
                            @if ($empresa->logo)
                                style="--empresa-logo: url('{{ asset('storage/' . $empresa->logo) }}')"
                            @endif
                            title="{{ $empresa->nombre }}"
                        >
                            <div class="empresa-directory-body">
                                <h3>{{ \Illuminate\Support\Str::limit($empresa->nombre, 16, '...') }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="empty-state">
                {{ $search !== '' ? 'No se encontraron P. Morales con ese criterio de busqueda.' : 'Todavia no hay P. Morales registradas.' }}
            </div>
        @endforelse
    </section>
@endsection
