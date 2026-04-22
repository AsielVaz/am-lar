@extends('layouts.app', [
    'title' => 'Editar empresa',
    'heading' => 'Editar empresa',
])

@section('content')
    @php($hasSociosCapturados = $empresa->socios->isNotEmpty())
    <div class="edit-shell">
        <section class="panel panel-elevated form-panel">
            <div class="panel-header panel-header-tight">
                <div>
                    <p class="panel-kicker">Actualizacion</p>
                    <h2>{{ $empresa->nombre }}</h2>
                </div>
                <a href="{{ route('empresas.show', $empresa) }}" class="button button-secondary">Ver empresa</a>
            </div>

            <form action="{{ route('empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data" class="form-grid">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_section" value="general">
                @unless($canEditCompanyData)
                    <div class="alert alert-success field-group-full">
                        Tu rol puede cargar y actualizar documentos, pero no modificar los datos generales ni el estatus de la empresa.
                    </div>
                @endunless
                @include('empresas.partials.form', ['hidePrimaryActions' => true])

                <div class="section-divider field-group-full">
                    <div>
                        <p class="panel-kicker">Documentacion</p>
                        <h3 class="section-title">Archivos legales y fiscales</h3>
                    </div>
                </div>

                @include('empresas.partials.documentos')

                <div class="form-actions form-actions-bottom field-group-full">
                    <a href="{{ route('empresas.index') }}" class="button button-secondary">Volver al listado</a>
                    <button type="submit" class="button button-primary">{{ $canEditCompanyData ? 'Guardar empresa y documentos' : 'Guardar documentos' }}</button>
                </div>
            </form>
        </section>

        <section class="panel panel-elevated form-panel {{ $hasSociosCapturados ? '' : 'is-missing-panel' }}" id="socios">
            <div class="panel-header panel-header-tight">
                <div>
                    <p class="panel-kicker">Socios</p>
                    <h2>Socios y representantes</h2>
                </div>
                <a href="{{ route('socios.create') }}" class="button button-secondary">Nuevo socio</a>
            </div>

            @unless($hasSociosCapturados)
                <div class="missing-panel-notice">
                    Esta empresa aun no tiene socios o representantes asignados.
                </div>
            @endunless

            <form action="{{ route('empresas.update', $empresa) }}" method="POST" class="panel-form-stack">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_section" value="partners">
                <input type="hidden" name="assignment_action" value="assign">

                <div class="form-section">
                    <div class="form-section-heading">
                        <p class="panel-kicker">Asignacion</p>
                        <h3 class="section-title">Asignar socio existente</h3>
                    </div>

                    <div class="section-grid">
                        <div class="field-group field-group-full">
                            <label for="socio_id">Socio disponible</label>
                            <select id="socio_id" name="socio_id" required>
                                <option value="">Selecciona un socio</option>
                                @foreach ($sociosDisponibles as $socioDisponible)
                                    <option value="{{ $socioDisponible->id }}">
                                        {{ $socioDisponible->nombre }} - {{ $socioDisponible->puesto }} - {{ $socioDisponible->rfc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('socio_id')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions form-actions-bottom">
                    <span class="helper-text">Selecciona un socio del catalogo y presiona en asignar socio.</span>
                    <button type="submit" class="button button-primary">Asignar socio</button>
                </div>
            </form>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Puesto</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empresa->socios as $socio)
                            <tr>
                                <td>{{ $socio->puesto }}</td>
                                <td>{{ $socio->nombre }}</td>
                                <td>{{ $socio->rfc }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('socios.show', $socio) }}" class="action-button action-button-view">Ver socio</a>
                                        <form
                                            action="{{ route('empresas.update', $empresa) }}"
                                            method="POST"
                                            class="inline-form"
                                            data-confirm-form
                                            data-confirm-title="Quitar socio"
                                            data-confirm-message="Se quitara a {{ $socio->nombre }} de esta empresa."
                                        >
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="form_section" value="partners">
                                            <input type="hidden" name="assignment_action" value="remove">
                                            <input type="hidden" name="socio_id" value="{{ $socio->id }}">
                                            <button type="submit" class="action-button action-button-delete">Quitar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">Esta empresa aun no tiene socios asignados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="form-actions form-actions-bottom">
                <span class="helper-text">La gestion completa de datos y archivos de socios ahora vive en el modulo de socios.</span>
                <a href="{{ route('socios.index') }}" class="button button-secondary">Ir a socios</a>
            </div>
        </section>
    </div>
@endsection
