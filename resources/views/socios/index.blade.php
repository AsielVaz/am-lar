@extends('layouts.app', [
    'title' => 'P. Fisicas',
    'heading' => 'P. Fisicas',
])

@section('topbar_actions')
    <div class="topbar-actions-stack">
        <div class="topbar-filter-group" aria-label="Filtrar por tipo de persona">
            <a href="{{ route('dashboard') }}" class="topbar-filter-button">
                P. Morales
            </a>
            <a href="{{ route('socios.index', $search !== '' ? ['search' => $search] : []) }}" class="topbar-filter-button is-active">
                P. Fisicas
            </a>
        </div>

        <div class="topbar-filter-group" aria-label="Estatus de P. Fisicas">
            <span class="topbar-filter-button is-active">Activas</span>
            <span class="topbar-filter-button">Inactivas</span>
            <span class="topbar-filter-button">Inertes</span>
        </div>
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
            <strong class="stat-value">{{ $estadisticas['representantes'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">P. Fisicas inactivas</span>
            <strong class="stat-value">{{ $estadisticas['accionarios'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">P. Fisicas inertes</span>
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
                    placeholder="Buscar P. Fisicas por nombre, RFC, direccion, puesto o P. Moral"
                    class="panel-search-input"
                >
                @if ($search !== '')
                    <a href="{{ route('socios.index') }}" class="button button-secondary">Limpiar</a>
                @endif
                <button type="submit" class="button button-primary">Buscar</button>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Puesto</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>P. Morales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($socios as $socio)
                        <tr>
                            <td>{{ $socio->puesto }}</td>
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
                            <td colspan="5" class="empty-state">Todavia no hay P. Fisicas registradas.</td>
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
