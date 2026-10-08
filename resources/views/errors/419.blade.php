@extends('layouts.app')
@section('title', 'El formulario ha caducado')
@section('content')
<section class="error-state">
    <span class="eyebrow">PORTAL DE INVENTARIO</span>
    <div class="error-number" aria-hidden="true">419</div>
    <h1>El formulario ha caducado</h1>
    <p>Tu sesión o el token del formulario ha caducado. Vuelve a iniciar sesión e intenta de nuevo.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio <span aria-hidden="true">→</span></a>
</section>
@endsection
