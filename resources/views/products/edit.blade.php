@extends('layouts.app')
@section('title', 'Editar producto')
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="mt-3 mb-4"><span class="eyebrow">CATÁLOGO</span><h1 class="fw-bold mt-2">Editar producto</h1><p class="text-secondary">Actualiza la información de {{ $product->name }}.</p></div>
<div class="card border-0 shadow-sm form-card"><div class="card-body p-4 p-lg-5">
    <form method="POST" action="{{ route('productos.update', $product) }}">
        @csrf
        @method('PUT')
        @include('products.form', ['submitLabel' => 'Guardar cambios'])
    </form>
</div></div>
@endsection
