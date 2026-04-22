@extends('layouts.app', [
    'title' => 'Editar socio',
    'heading' => 'Editar socio',
])

@section('content')
    <section class="panel panel-form">
        <div class="panel-header panel-header-tight">
            <div>
                <p class="panel-kicker">Catalogo</p>
                <h2>Actualizar socio</h2>
            </div>
            <a href="{{ route('socios.show', $socio) }}" class="button button-secondary">Ver socio</a>
        </div>

        <form action="{{ route('socios.update', $socio) }}" method="POST" enctype="multipart/form-data" class="auth-admin-form">
            @csrf
            @method('PUT')
            @include('socios.partials.form', ['isEdit' => true])
        </form>
    </section>
@endsection
