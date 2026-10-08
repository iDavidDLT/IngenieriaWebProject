@extends('layouts.app')
@section('title', 'Acceso denegado')
@section('content')
<section class="error-state">
    <span class="eyebrow">PORTAL DE INVENTARIO</span>
    <div class="error-number" aria-hidden="true">403</div>
    <h1>Acceso denegado</h1>
    <p>No tienes permiso para acceder a este registro. Puedes volver a tu inventario y consultar tus productos.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio <span aria-hidden="true">→</span></a>
</section>
@endsection
