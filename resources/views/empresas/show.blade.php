@extends('layouts.app', [
    'title' => 'Detalle de P. Moral',
    'heading' => 'Detalle de P. Moral',
])

@section('content')
    @php($representanteLegal = $empresa->socios->first(fn ($socio) => $socio->pivot?->puesto === 'Reprecentante legal'))
    @php($sociosAccionarios = $empresa->socios->filter(fn ($socio) => $socio->pivot?->puesto === 'Socio accionario')->values())
    @php($sociosSlots = collect([
        ['label' => 'Representante legal', 'helper' => 'Slot unico', 'socio' => $representanteLegal],
        ['label' => 'Socio 1', 'helper' => 'Primer socio accionario', 'socio' => $sociosAccionarios->get(0)],
        ['label' => 'Socio 2', 'helper' => 'Segundo socio accionario', 'socio' => $sociosAccionarios->get(1)],
    ]))
    <div class="detail-stack">
        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Consulta</p>
                    <h2>{{ $empresa->nombre }}</h2>
                </div>
                @if (! auth()->user()->isUsuario())
                    <a href="{{ route('empresas.edit', $empresa) }}" class="button button-primary">Editar</a>
                @endif
            </div>

            <div class="detail-grid">
                <div class="detail-logo {{ $empresa->logo ? '' : 'is-missing' }}">
                    @if ($empresa->logo)
                        <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo de {{ $empresa->nombre }}" class="logo-preview">
                    @else
                        <div class="logo-placeholder logo-placeholder-lg">{{ strtoupper(substr($empresa->nombre, 0, 2)) }}</div>
                    @endif
                </div>

                <div class="detail-card">
                    <span class="detail-label">Nombre</span>
                    <strong>{{ $empresa->nombre }}</strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">RFC</span>
                    <strong>{{ $empresa->rfc }}</strong>
                </div>
                <div class="detail-card detail-card-full">
                    <span class="detail-label">Direccion</span>
                    <strong>{{ $empresa->direccion }}</strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">Codigo postal</span>
                    <strong>{{ $empresa->codigo_postal }}</strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">Estatus</span>
                    <strong><span class="badge badge-{{ $empresa->estatus }}">{{ ucfirst($empresa->estatus) }}</span></strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">Prioridad</span>
                    <strong>{{ ucfirst($empresa->prioridad) }}</strong>
                </div>
                <div class="detail-card {{ blank($empresa->telefono) ? 'is-missing' : '' }}">
                    <span class="detail-label">Telefono</span>
                    <strong class="{{ blank($empresa->telefono) ? 'missing-glass-text' : '' }}">{{ $empresa->telefono ?: 'No capturado' }}</strong>
                </div>
                <div class="detail-card {{ blank($empresa->correo) ? 'is-missing' : '' }}">
                    <span class="detail-label">Correo</span>
                    <strong class="{{ blank($empresa->correo) ? 'missing-glass-text' : '' }}">{{ $empresa->correo ?: 'No capturado' }}</strong>
                </div>
                <div class="detail-card {{ blank($empresa->sitio_web) ? 'is-missing' : '' }}">
                    <span class="detail-label">Sitio web</span>
                    <strong class="{{ blank($empresa->sitio_web) ? 'missing-glass-text' : '' }}">{{ $empresa->sitio_web ?: 'No capturado' }}</strong>
                </div>
                <div class="detail-card {{ blank($empresa->fin_dominio_web) ? 'is-missing' : '' }}">
                    <span class="detail-label">Fin del dominio web</span>
                    <strong class="{{ blank($empresa->fin_dominio_web) ? 'missing-glass-text' : '' }}">{{ $empresa->fin_dominio_web ?: 'No capturado' }}</strong>
                </div>
                <div class="detail-card detail-card-full {{ blank($empresa->contrasena_iofacturo) ? 'is-missing' : '' }}">
                    <span class="detail-label">Contrasena IOFacturo</span>
                    <strong class="{{ blank($empresa->contrasena_iofacturo) ? 'missing-glass-text' : '' }}">{{ $empresa->contrasena_iofacturo ?: 'No capturada' }}</strong>
                </div>
            </div>
        </section>

        @php($documentos = $empresa->documentos)

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Legales</p>
                    <h2>Asambleas y registros</h2>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-card {{ blank($documentos?->acta_constitutiva_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">Acta constitutiva y asambleas</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->acta_constitutiva_pdf])
                </div>
                <div class="detail-card {{ blank($documentos?->registro_publico_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">Registro publico de la propiedad y comercio</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->registro_publico_pdf])
                </div>
            </div>
        </section>

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">P. Fisicas</p>
                    <h2>P. Fisicas y representantes</h2>
                </div>
            </div>

            <div class="slot-summary">
                <span class="panel-chip">Slots ocupados: {{ $empresa->socios->count() }}/3</span>
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
                                <h4>
                                    @if (! auth()->user()->isUsuario())
                                        <a href="{{ route('socios.show', $socio) }}" class="document-link">{{ $socio->nombre }}</a>
                                    @else
                                        {{ $socio->nombre }}
                                    @endif
                                </h4>
                                <p><strong>RFC:</strong> {{ $socio->rfc ?: 'No capturado' }}</p>
                                <p><strong>Direccion:</strong> {{ $socio->direccion ?: 'No capturada' }}</p>
                                <p><strong>Contrasena:</strong> {{ $socio->contrasena ?: 'No capturada' }}</p>
                            </div>

                            <div class="relation-slot-files">
                                <div>
                                    <span class="detail-label">INE (PDF)</span>
                                    @include('empresas.partials.document-link', ['archivo' => $socio->ine_pdf])
                                </div>
                                <div>
                                    <span class="detail-label">CSF (PDF)</span>
                                    @include('empresas.partials.document-link', ['archivo' => $socio->csf_pdf])
                                </div>
                                <div>
                                    <span class="detail-label">Certificado .cer</span>
                                    @include('empresas.partials.document-link', ['archivo' => $socio->certificado_cer])
                                </div>
                                <div>
                                    <span class="detail-label">Llave .key</span>
                                    @include('empresas.partials.document-link', ['archivo' => $socio->llave_key])
                                </div>
                            </div>
                        @else
                            <div class="relation-slot-empty">
                                <span>{{ $slot['label'] }} disponible</span>
                                <p>Este slot sigue visible para mostrar la estructura fija de 1 Representante legal y 2 socios por P. Moral.</p>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Documentacion</p>
                    <h2>Archivos fiscales y complementarios</h2>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-card {{ blank($documentos?->d32_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">32D</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->d32_pdf])
                </div>
                <div class="detail-card {{ blank($documentos?->d32_caducidad) ? 'is-missing' : '' }}">
                    <span class="detail-label">Caducidad de 32D</span>
                    <strong class="{{ blank($documentos?->d32_caducidad) ? 'missing-glass-text' : '' }}">{{ $documentos?->d32_caducidad?->format('d/m/Y') ?: 'No calculada' }}</strong>
                </div>
                <div class="detail-card {{ blank($documentos?->sello_sat_key) ? 'is-missing' : '' }}">
                    <span class="detail-label">Sello SAT .key</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->sello_sat_key])
                </div>
                <div class="detail-card {{ blank($documentos?->sello_sat_key_contrasena) ? 'is-missing' : '' }}">
                    <span class="detail-label">Contrasena de Sello SAT .key</span>
                    <strong class="{{ blank($documentos?->sello_sat_key_contrasena) ? 'missing-glass-text' : '' }}">{{ $documentos?->sello_sat_key_contrasena ?: 'No capturada' }}</strong>
                </div>
                <div class="detail-card {{ blank($documentos?->sello_sat_cer) ? 'is-missing' : '' }}">
                    <span class="detail-label">Sello SAT .cer</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->sello_sat_cer])
                </div>
                <div class="detail-card {{ blank($documentos?->fiel_key) ? 'is-missing' : '' }}">
                    <span class="detail-label">FIEL .key</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->fiel_key])
                </div>
                <div class="detail-card {{ blank($documentos?->fiel_key_contrasena) ? 'is-missing' : '' }}">
                    <span class="detail-label">Contrasena de FIEL .key</span>
                    <strong class="{{ blank($documentos?->fiel_key_contrasena) ? 'missing-glass-text' : '' }}">{{ $documentos?->fiel_key_contrasena ?: 'No capturada' }}</strong>
                </div>
                <div class="detail-card {{ blank($documentos?->fiel_cer) ? 'is-missing' : '' }}">
                    <span class="detail-label">FIEL .cer</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->fiel_cer])
                </div>
                <div class="detail-card detail-card-full {{ blank($documentos?->comprobante_domicilio_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">Comprobante de domicilio</span>
                    @include('empresas.partials.document-link', ['archivo' => $documentos?->comprobante_domicilio_pdf])
                </div>
                <div class="detail-card detail-card-full {{ blank($documentos?->comprobante_domicilio_caducidad) ? 'is-missing' : '' }}">
                    <span class="detail-label">Caducidad de comprobante de domicilio</span>
                    <strong class="{{ blank($documentos?->comprobante_domicilio_caducidad) ? 'missing-glass-text' : '' }}">{{ $documentos?->comprobante_domicilio_caducidad?->format('d/m/Y') ?: 'No calculada' }}</strong>
                </div>
            </div>
        </section>
    </div>
@endsection
