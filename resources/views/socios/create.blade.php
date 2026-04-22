@extends('layouts.app', [
    'title' => 'Nueva P. Fisica',
    'heading' => 'Nueva P. Fisica',
])

@section('content')
    <section class="panel panel-form">
        <div class="panel-header panel-header-tight">
            <div>
                <p class="panel-kicker">Catalogo</p>
                <h2>Registrar P. Fisica</h2>
            </div>
        </div>

        <form action="{{ route('socios.store') }}" method="POST" enctype="multipart/form-data" class="auth-admin-form">
            @csrf
            @include('socios.partials.form', ['isEdit' => false])
        </form>
    </section>
@endsection
