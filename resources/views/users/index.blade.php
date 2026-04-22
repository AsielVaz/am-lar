@extends('layouts.app', [
    'title' => 'Usuarios',
    'heading' => 'Usuarios',
])

@section('content')
    <section class="stats-grid stats-grid-users">
        <article class="stat-card">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $estadisticas['total'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Administradores</span>
            <strong class="stat-value">{{ $estadisticas['administradores'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Capturistas</span>
            <strong class="stat-value">{{ $estadisticas['capturistas'] }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-label">Usuarios</span>
            <strong class="stat-value">{{ $estadisticas['usuarios'] }}</strong>
        </article>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">Administracion</p>
                <h2>Listado de usuarios</h2>
            </div>
            <a href="{{ route('users.create') }}" class="button button-primary">Nuevo usuario</a>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Alta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge badge-activa">{{ \App\Models\User::roles()[$usuario->role] ?? ucfirst($usuario->role) }}</span>
                            </td>
                            <td>{{ optional($usuario->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('users.edit', $usuario) }}" class="action-button action-button-edit">Editar</a>
                                    <form
                                        action="{{ route('users.destroy', $usuario) }}"
                                        method="POST"
                                        class="inline-form"
                                        data-confirm-form
                                        data-confirm-title="Eliminar usuario"
                                        data-confirm-message="Se eliminara el usuario {{ $usuario->email }}. Esta accion no se puede deshacer."
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-button action-button-delete">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Todavia no hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $usuarios->links('components.panel-pagination') }}
        </div>
    </section>
@endsection
