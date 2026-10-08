@extends('layouts.app')
@section('title', 'Página no encontrada')
@section('content')
<div class="card border-0 shadow-sm p-4 p-lg-5 text-center">
    <span class="eyebrow">ERROR 404</span>
    <h1 class="h2 fw-bold mt-3">Página no encontrada</h1>
    <p class="text-secondary">El registro o la página solicitada no existe.</p>
    <div><a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio</a></div>
</div>
@endsection
