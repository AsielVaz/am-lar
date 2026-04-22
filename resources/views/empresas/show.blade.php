@extends('layouts.app', [
    'title' => 'Detalle de P. Moral',
    'heading' => 'Detalle de P. Moral',
])

@section('content')
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

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Puesto</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>RFC</th>
                            <th>Contrasena</th>
                            <th>INE (PDF)</th>
                            <th>CSF (PDF)</th>
                            <th>Certificado .cer</th>
                            <th>Llave .key</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empresa->socios as $socio)
                            <tr>
                                <td>{!! filled($socio->pivot?->puesto) ? e($socio->pivot?->puesto) : '<span class="table-missing">No capturado</span>' !!}</td>
                                <td>
                                    @if (filled($socio->nombre) && ! auth()->user()->isUsuario())
                                        <a href="{{ route('socios.show', $socio) }}" class="document-link">{{ $socio->nombre }}</a>
                                    @elseif (filled($socio->nombre))
                                        {{ $socio->nombre }}
                                    @else
                                        <span class="table-missing">No capturado</span>
                                    @endif
                                </td>
                                <td>{!! filled($socio->direccion) ? e($socio->direccion) : '<span class="table-missing">No capturada</span>' !!}</td>
                                <td>{!! filled($socio->rfc) ? e($socio->rfc) : '<span class="table-missing">No capturado</span>' !!}</td>
                                <td>{!! filled($socio->contrasena) ? e($socio->contrasena) : '<span class="table-missing">No capturada</span>' !!}</td>
                                <td>@include('empresas.partials.document-link', ['archivo' => $socio->ine_pdf])</td>
                                <td>@include('empresas.partials.document-link', ['archivo' => $socio->csf_pdf])</td>
                                <td>@include('empresas.partials.document-link', ['archivo' => $socio->certificado_cer])</td>
                                <td>@include('empresas.partials.document-link', ['archivo' => $socio->llave_key])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">No hay P. Fisicas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
