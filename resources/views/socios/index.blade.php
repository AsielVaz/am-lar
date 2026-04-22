@extends('layouts.app', [
    'title' => 'P. Fisicas',
    'heading' => 'P. Fisicas',
])

@section('topbar_leading_actions')
    <div class="topbar-filter-group" aria-label="Filtrar por tipo de persona">
        <a href="{{ route('dashboard') }}" class="topbar-filter-button">
            P. Morales
        </a>
        <a href="{{ route('socios.index', $search !== '' ? ['search' => $search] : []) }}" class="topbar-filter-button is-active">
            P. Fisicas
        </a>
    </div>
@endsection

@section('topbar_actions')
    <div class="topbar-filter-group" aria-label="Estatus de P. Fisicas">
        <a href="{{ route('socios.index', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'activa'])) }}" class="topbar-filter-button {{ ($estatus ?? 'activa') === 'activa' ? 'is-active' : '' }}">Activas</a>
        <a href="{{ route('socios.index', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'inactiva'])) }}" class="topbar-filter-button {{ ($estatus ?? '') === 'inactiva' ? 'is-active' : '' }}">Inactivas</a>
        <a href="{{ route('socios.index', array_filter(['search' => $search !== '' ? $search : null, 'estatus' => 'inerte'])) }}" class="topbar-filter-button {{ ($estatus ?? '') === 'inerte' ? 'is-active' : '' }}">Inertes</a>
    </div>
@endsection

@section('content')
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-label">Total P. Fisicas</span>
            <strong class="stat-value">{{ $estadisticas['total'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">P. Fisicas activas</span>
            <strong class="stat-value">{{ $estadisticas['activas'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">P. Fisicas inactivas</span>
            <strong class="stat-value">{{ $estadisticas['inactivas'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">P. Fisicas inertes</span>
            <strong class="stat-value">{{ $estadisticas['inertes'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Asignadas a P. Morales</span>
            <strong class="stat-value">{{ $estadisticas['asignados'] }}</strong>
        </article>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">Catalogo</p>
                <h2>Listado de P. Fisicas</h2>
            </div>
            <a href="{{ route('socios.create') }}" class="button button-primary">Nueva P. Fisica</a>
        </div>

        <form action="{{ route('socios.index') }}" method="GET" class="panel-search-form">
            <div class="panel-search-group">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Buscar P. Fisicas por nombre, RFC, direccion o P. Moral"
                    class="panel-search-input"
                >
                <input type="hidden" name="estatus" value="{{ $estatus }}">
                @if ($search !== '')
                    <a href="{{ route('socios.index', ['estatus' => $estatus]) }}" class="button button-secondary">Limpiar</a>
                @endif
                <button type="submit" class="button button-primary">Buscar</button>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Estatus</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>P. Morales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($socios as $socio)
                        <tr>
                            <td>
                                @if ($socio->foto_usuario)
                                    <img src="{{ asset('storage/' . $socio->foto_usuario) }}" alt="Foto de {{ $socio->nombre }}" class="logo-thumb">
                                @else
                                    <div class="logo-placeholder">{{ strtoupper(substr($socio->nombre, 0, 2)) }}</div>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $socio->estatus }}">{{ ucfirst($socio->estatus) }}</span></td>
                            <td>{{ $socio->nombre }}</td>
                            <td>{{ $socio->rfc }}</td>
                            <td>{{ $socio->empresas_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('socios.show', $socio) }}" class="action-button action-button-view">Ver</a>
                                    <a href="{{ route('socios.edit', $socio) }}" class="action-button action-button-edit">Editar</a>
                                    @if (auth()->user()->isAdministrador())
                                        <form
                                            action="{{ route('socios.destroy', $socio) }}"
                                            method="POST"
                                            class="inline-form"
                                            data-confirm-form
                                            data-confirm-title="Eliminar P. Fisica"
                                            data-confirm-message="Se eliminara {{ $socio->nombre }} y se quitaran sus relaciones con P. Morales. Esta accion no se puede deshacer."
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-button action-button-delete">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Todavia no hay P. Fisicas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $socios->links('components.panel-pagination') }}
        </div>
    </section>
@endsection
