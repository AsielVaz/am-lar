@extends('layouts.app', [
    'title' => 'Editar P. Moral',
    'heading' => 'Editar P. Moral',
])

@section('content')
    @php($hasSociosCapturados = $empresa->socios->isNotEmpty())
    @php($sociosSlotsOcupados = $empresa->socios->count())
    @php($representanteLegal = $empresa->socios->first(fn ($socio) => $socio->pivot?->puesto === 'Reprecentante legal'))
    @php($sociosAccionarios = $empresa->socios->filter(fn ($socio) => $socio->pivot?->puesto === 'Socio accionario')->values())
    @php($sociosSlots = collect([
        ['label' => 'Representante legal', 'helper' => 'Slot unico', 'socio' => $representanteLegal],
        ['label' => 'Socio 1', 'helper' => 'Primer socio accionario', 'socio' => $sociosAccionarios->get(0)],
        ['label' => 'Socio 2', 'helper' => 'Segundo socio accionario', 'socio' => $sociosAccionarios->get(1)],
    ]))
    <div class="edit-shell">
        <section class="panel panel-elevated form-panel">
            <div class="panel-header panel-header-tight">
                <div>
                    <p class="panel-kicker">Actualizacion</p>
                    <h2>{{ $empresa->nombre }}</h2>
                </div>
                <a href="{{ route('empresas.show', $empresa) }}" class="button button-secondary">Ver P. Moral</a>
            </div>

            <form action="{{ route('empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data" class="form-grid">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_section" value="general">
                @unless($canEditCompanyData)
                    <div class="alert alert-success field-group-full">
                        Tu rol puede cargar y actualizar documentos, pero no modificar los datos generales ni el estatus de la P. Moral.
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
                    <button type="submit" class="button button-primary">{{ $canEditCompanyData ? 'Guardar P. Moral y documentos' : 'Guardar documentos' }}</button>
                </div>
            </form>
        </section>

        <section class="panel panel-elevated form-panel {{ $hasSociosCapturados ? '' : 'is-missing-panel' }}" id="socios">
            <div class="panel-header panel-header-tight">
                <div>
                    <p class="panel-kicker">P. Fisicas</p>
                    <h2>P. Fisicas y representantes</h2>
                </div>
                <a href="{{ route('socios.create') }}" class="button button-secondary">Nueva P. Fisica</a>
            </div>

            @unless($hasSociosCapturados)
                <div class="missing-panel-notice">
                    Esta P. Moral aun no tiene P. Fisicas o representantes asignados.
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
                        <h3 class="section-title">Asignar P. Fisica existente</h3>
                    </div>

                    <div class="section-grid">
                        <div class="field-group">
                            <label for="socio_id">P. Fisica disponible</label>
                            <select id="socio_id" name="socio_id" required>
                                <option value="">Selecciona una P. Fisica</option>
                                @foreach ($sociosDisponibles as $socioDisponible)
                                    <option value="{{ $socioDisponible->id }}">
                                        {{ $socioDisponible->nombre }} - {{ $socioDisponible->rfc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('socio_id')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="puesto">Puesto en esta P. Moral</label>
                            <select id="puesto" name="puesto" required>
                                <option value="">Selecciona un puesto</option>
                                <option value="Reprecentante legal" @selected(old('puesto') === 'Reprecentante legal')>Reprecentante legal</option>
                                <option value="Socio accionario" @selected(old('puesto') === 'Socio accionario')>Socio accionario</option>
                            </select>
                            @error('puesto')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions form-actions-bottom">
                    <span class="helper-text">Cada P. Moral puede tener como maximo 1 Representante legal y 2 socios accionarios.</span>
                    <button type="submit" class="button button-primary">Asignar P. Fisica</button>
                </div>
            </form>

            <div class="slot-summary">
                <span class="panel-chip">Slots ocupados: {{ $sociosSlotsOcupados }}/3</span>
            </div>

            <div class="relation-slot-grid">
                @foreach ($sociosSlots as $slot)
                    @php($socio = $slot['socio'])
                    <article class="relation-slot-card {{ $socio ? '' : 'is-empty' }}">
                        <div class="relation-slot-header">
                            <span class="panel-kicker">{{ $slot['helper'] }}</span>
                            <strong>{{ $slot['label'] }}</strong>
                        </div>

                        @if ($socio)
                            <div class="relation-slot-body">
                                <h4>{{ $socio->nombre }}</h4>
                                <p>{{ $socio->rfc }}</p>
                                <span class="badge badge-{{ $socio->estatus }}">{{ ucfirst($socio->estatus) }}</span>
                            </div>

                            <div class="table-actions">
                                <a href="{{ route('socios.show', $socio) }}" class="action-button action-button-view">Ver P. Fisica</a>
                                <form
                                    action="{{ route('empresas.update', $empresa) }}"
                                    method="POST"
                                    class="inline-form"
                                    data-confirm-form
                                    data-confirm-title="Quitar P. Fisica"
                                    data-confirm-message="Se quitara a {{ $socio->nombre }} de esta P. Moral."
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_section" value="partners">
                                    <input type="hidden" name="assignment_action" value="remove">
                                    <input type="hidden" name="socio_id" value="{{ $socio->id }}">
                                    <button type="submit" class="action-button action-button-delete">Quitar</button>
                                </form>
                            </div>
                        @else
                            <div class="relation-slot-empty">
                                <span>{{ $slot['label'] }} disponible</span>
                                <p>Este slot seguira visible hasta que se asigne una P. Fisica a esta posicion.</p>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="form-actions form-actions-bottom">
                <span class="helper-text">La gestion completa de datos y archivos de P. Fisicas ahora vive en el modulo de P. Fisicas.</span>
                <a href="{{ route('socios.index') }}" class="button button-secondary">Ir a P. Fisicas</a>
            </div>
        </section>
    </div>
@endsection
