@extends('layouts.app', [
    'title' => 'Detalle de socio',
    'heading' => 'Detalle de socio',
])

@section('content')
    <div class="detail-stack">
        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Consulta</p>
                    <h2>{{ $socio->nombre }}</h2>
                </div>
                @if (! auth()->user()->isUsuario())
                    <a href="{{ route('socios.edit', $socio) }}" class="button button-primary">Editar</a>
                @endif
            </div>

            <div class="detail-grid">
                <div class="detail-card">
                    <span class="detail-label">Puesto</span>
                    <strong>{{ $socio->puesto }}</strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">RFC</span>
                    <strong>{{ $socio->rfc }}</strong>
                </div>
                <div class="detail-card detail-card-full">
                    <span class="detail-label">Direccion</span>
                    <strong>{{ $socio->direccion }}</strong>
                </div>
                <div class="detail-card {{ blank($socio->contrasena) ? 'is-missing' : '' }}">
                    <span class="detail-label">Contrasena</span>
                    <strong class="{{ blank($socio->contrasena) ? 'missing-glass-text' : '' }}">{{ $socio->contrasena ?: 'No capturada' }}</strong>
                </div>
                <div class="detail-card">
                    <span class="detail-label">Empresas asignadas</span>
                    <strong>{{ $socio->empresas->count() }}</strong>
                </div>
            </div>
        </section>

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Documentacion</p>
                    <h2>Archivos del socio</h2>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-card {{ blank($socio->ine_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">INE (PDF)</span>
                    @include('empresas.partials.document-link', ['archivo' => $socio->ine_pdf])
                </div>
                <div class="detail-card {{ blank($socio->csf_pdf) ? 'is-missing' : '' }}">
                    <span class="detail-label">CSF (PDF)</span>
                    @include('empresas.partials.document-link', ['archivo' => $socio->csf_pdf])
                </div>
                <div class="detail-card {{ blank($socio->certificado_cer) ? 'is-missing' : '' }}">
                    <span class="detail-label">Certificado .cer</span>
                    @include('empresas.partials.document-link', ['archivo' => $socio->certificado_cer])
                </div>
                <div class="detail-card {{ blank($socio->llave_key) ? 'is-missing' : '' }}">
                    <span class="detail-label">Llave .key</span>
                    @include('empresas.partials.document-link', ['archivo' => $socio->llave_key])
                </div>
            </div>
        </section>

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Relaciones</p>
                    <h2>Empresas asignadas</h2>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>RFC</th>
                            <th>Estatus</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($socio->empresas as $empresa)
                            <tr>
                                <td>{{ $empresa->nombre }}</td>
                                <td>{{ $empresa->rfc }}</td>
                                <td><span class="badge badge-{{ $empresa->estatus }}">{{ ucfirst($empresa->estatus) }}</span></td>
                                <td><a href="{{ route('empresas.show', $empresa) }}" class="action-button action-button-view">Ver empresa</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">Este socio aun no esta asignado a ninguna empresa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
