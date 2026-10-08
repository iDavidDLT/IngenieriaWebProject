@extends('layouts.app')
@section('title', 'El formulario ha caducado')
@section('content')
<div class="card border-0 shadow-sm p-4 p-lg-5 text-center">
    <span class="eyebrow">ERROR 419</span>
    <h1 class="h2 fw-bold mt-3">El formulario ha caducado</h1>
    <p class="text-secondary">Tu sesión o el token del formulario ha caducado. Vuelve a iniciar sesión e intenta de nuevo.</p>
    <div><a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio</a></div>
</div>
@endsection
