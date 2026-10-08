@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="mt-3 mb-4"><span class="eyebrow">CATÁLOGO</span><h1 class="fw-bold mt-2">Nuevo producto</h1><p class="text-secondary">Completa los datos para agregar un producto al inventario.</p></div>
<div class="card border-0 shadow-sm form-card"><div class="card-body p-4 p-lg-5">
    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        @include('products.form', ['submitLabel' => 'Guardar producto'])
    </form>
</div></div>
@endsection
