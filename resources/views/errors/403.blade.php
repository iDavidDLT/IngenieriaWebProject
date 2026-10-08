@extends('layouts.app')
@section('title', 'Acceso denegado')
@section('content')
<div class="card border-0 shadow-sm p-4 p-lg-5 text-center">
    <span class="eyebrow">ERROR 403</span>
    <h1 class="h2 fw-bold mt-3">Acceso denegado</h1>
    <p class="text-secondary">No tienes permiso para acceder a este registro.</p>
    <div><a class="btn btn-primary" href="{{ url('/') }}">Volver al inicio</a></div>
</div>
@endsection
