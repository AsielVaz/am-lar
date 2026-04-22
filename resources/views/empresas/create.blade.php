@extends('layouts.app', [
    'title' => 'Nueva P. Moral',
    'heading' => 'Registrar P. Moral',
])

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <p class="panel-kicker">Alta</p>
                <h2>Nueva P. Moral</h2>
            </div>
        </div>

        <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
            @csrf
            @include('empresas.partials.form')
        </form>
    </section>
@endsection
