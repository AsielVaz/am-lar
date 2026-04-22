@extends('layouts.app', [
    'title' => 'Empresas',
    'heading' => 'Empresas',
])

@section('content')
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-label">Total</span>
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

    <section class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">CRUD completo</p>
                <h2>Listado de empresas</h2>
            </div>
            @if (auth()->user()->isAdministrador())
                <a href="{{ route('empresas.create') }}" class="button button-primary">Nueva empresa</a>
            @endif
        </div>

        <form action="{{ route('empresas.index') }}" method="GET" class="panel-search-form">
            <div class="panel-search-group">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Buscar por nombre, RFC, direccion o correo"
                    class="panel-search-input"
                >
                @if ($search !== '')
                    <a href="{{ route('empresas.index') }}" class="button button-secondary">Limpiar</a>
                @endif
                <button type="submit" class="button button-primary">Buscar</button>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Codigo postal</th>
                        <th>Estatus</th>
                        <th>Prioridad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empresas as $empresa)
                        <tr>
                            <td class="empresa-name-cell">
                                <div
                                    class="empresa-name-chip {{ $empresa->logo ? 'has-logo' : 'no-logo' }}"
                                    @if ($empresa->logo)
                                        style="--empresa-logo: url('{{ asset('storage/' . $empresa->logo) }}')"
                                    @endif
                                    title="{{ $empresa->nombre }}"
                                >
                                    <span>{{ $empresa->nombre }}</span>
                                </div>
                            </td>
                            <td>{{ $empresa->rfc }}</td>
                            <td>{{ $empresa->codigo_postal }}</td>
                            <td><span class="badge badge-{{ $empresa->estatus }}">{{ ucfirst($empresa->estatus) }}</span></td>
                            <td>{{ ucfirst($empresa->prioridad) }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('empresas.edit', $empresa) }}" class="action-button action-button-edit">Editar</a>
                                    @if (auth()->user()->isAdministrador())
                                        <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="inline-form" data-confirm-form data-confirm-title="Eliminar empresa" data-confirm-message="Se eliminara {{ $empresa->nombre }} y toda su informacion relacionada. Esta accion no se puede deshacer.">
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
                            <td colspan="6" class="empty-state">
                                {{ $search !== '' ? 'No se encontraron empresas con ese criterio de busqueda.' : 'No hay empresas registradas todavia.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $empresas->links('components.panel-pagination') }}
        </div>
    </section>
@endsection
