@extends('layouts.app', [
    'title' => 'Editar usuario',
    'heading' => 'Editar usuario',
])

@section('content')
    <section class="panel panel-form">
        <div class="panel-header panel-header-tight">
            <div>
                <p class="panel-kicker">Acceso al sistema</p>
                <h2>Actualizar usuario</h2>
            </div>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="auth-admin-form">
            @csrf
            @method('PUT')
            @include('users.partials.form', ['isEdit' => true])
        </form>
    </section>
@endsection
