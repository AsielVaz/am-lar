@extends('layouts.app', [
    'title' => 'Detalle de P. Fisica',
    'heading' => 'Detalle de P. Fisica',
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
                <div class="detail-logo {{ $socio->foto_usuario ? '' : 'is-missing' }}">
                    @if ($socio->foto_usuario)
                        <img src="{{ asset('storage/' . $socio->foto_usuario) }}" alt="Foto de {{ $socio->nombre }}" class="logo-preview">
                    @else
                        <div class="logo-placeholder logo-placeholder-lg">{{ strtoupper(substr($socio->nombre, 0, 2)) }}</div>
                    @endif
                </div>
                <div class="detail-card">
                    <span class="detail-label">Estatus</span>
                    <strong><span class="badge badge-{{ $socio->estatus }}">{{ ucfirst($socio->estatus) }}</span></strong>
                </div>
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
                    <span class="detail-label">P. Morales asignadas</span>
                    <strong>{{ $socio->empresas->count() }}</strong>
                </div>
            </div>
        </section>

        <section class="panel detail-panel">
            <div class="panel-header">
                <div>
                    <p class="panel-kicker">Documentacion</p>
                    <h2>Archivos de la P. Fisica</h2>
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
                    <h2>P. Morales asignadas</h2>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>P. Moral</th>
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
                                <td><a href="{{ route('empresas.show', $empresa) }}" class="action-button action-button-view">Ver P. Moral</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">Esta P. Fisica aun no esta asignada a ninguna P. Moral.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
