@extends('layouts.app')
@section('title', 'Página no encontrada')
@section('content')
<section class="error-state">
    <span class="eyebrow">PORTAL DE INVENTARIO</span>
    <div class="error-number" aria-hidden="true">404</div>
    <h1>Página no encontrada</h1>
    <p>El registro o la página solicitada no existe. Comprueba la dirección o vuelve al inicio.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio <span aria-hidden="true">→</span></a>
</section>
@endsection
