@extends('layouts.app', [
    'title' => 'Socios',
    'heading' => 'Socios',
])

@section('content')
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $estadisticas['total'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Representantes legales</span>
            <strong class="stat-value">{{ $estadisticas['representantes'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Socios accionarios</span>
            <strong class="stat-value">{{ $estadisticas['accionarios'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Asignados a empresas</span>
            <strong class="stat-value">{{ $estadisticas['asignados'] }}</strong>
        </article>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">Catalogo</p>
                <h2>Listado de socios</h2>
            </div>
            <a href="{{ route('socios.create') }}" class="button button-primary">Nuevo socio</a>
        </div>

        <form action="{{ route('socios.index') }}" method="GET" class="panel-search-form">
            <div class="panel-search-group">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Buscar por nombre, RFC, direccion, puesto o empresa"
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
                        <th>Empresas</th>
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
                                            data-confirm-title="Eliminar socio"
                                            data-confirm-message="Se eliminara {{ $socio->nombre }} y se quitaran sus relaciones con empresas. Esta accion no se puede deshacer."
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
                            <td colspan="5" class="empty-state">Todavia no hay socios registrados.</td>
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
