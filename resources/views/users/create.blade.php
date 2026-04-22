@extends('layouts.app', [
    'title' => 'Nuevo usuario',
    'heading' => 'Nuevo usuario',
])

@section('content')
    <section class="panel panel-form">
        <div class="panel-header panel-header-tight">
            <div>
                <p class="panel-kicker">Acceso al sistema</p>
                <h2>Registrar usuario</h2>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="auth-admin-form">
            @csrf
            @include('users.partials.form', ['isEdit' => false])
        </form>
    </section>
@endsection
