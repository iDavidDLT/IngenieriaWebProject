@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="page-heading"><div><span class="eyebrow">AMPLÍA TU CATÁLOGO</span><h1 class="page-title">Nuevo producto</h1><p class="page-subtitle">Registra la información del producto y sus existencias iniciales.</p></div><span class="section-kicker">NUEVO REGISTRO</span></div>
<div class="form-layout">
    <section class="panel">
        <div class="panel-heading"><div><h2>Información del producto</h2><p>Los campos con asterisco son obligatorios.</p></div></div>
        <form method="POST" action="{{ route('productos.store') }}">
            @csrf
            @include('products.form', ['submitLabel' => 'Guardar producto'])
        </form>
    </section>
    @include('products.form-aside')
</div>
@endsection
