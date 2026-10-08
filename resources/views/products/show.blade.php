@extends('layouts.app')
@section('title', $product->name)
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mt-3 mb-4">
    <div><span class="eyebrow">DETALLE DEL PRODUCTO</span><h1 class="fw-bold mt-2 mb-0">{{ $product->name }}</h1></div>
    <a class="btn btn-primary" href="{{ route('productos.edit', $product) }}">Editar producto</a>
</div>
<div class="card border-0 shadow-sm form-card"><div class="card-body p-4 p-lg-5">
    <div class="row g-4">
        <div class="col-md-4"><div class="text-secondary small mb-2">CÓDIGO SKU</div><code class="sku fs-5">{{ $product->sku }}</code></div>
        <div class="col-md-4"><div class="text-secondary small mb-2">PRECIO (USD)</div><span class="fs-3 fw-bold">$ {{ number_format((float) $product->price, 2) }}</span></div>
        <div class="col-md-4"><div class="text-secondary small mb-2">EXISTENCIAS</div><span class="badge {{ $product->stock <= 5 ? 'text-bg-warning' : 'stock-good' }} fs-6">{{ $product->stock }} unidades</span></div>
        <div class="col-12 border-top pt-4"><h2 class="h6 text-secondary">Descripción</h2><p class="description mb-0">{{ $product->description ?: 'Sin descripción registrada.' }}</p></div>
    </div>
    <div class="border-top mt-4 pt-3 small text-secondary">Creado: {{ $product->created_at->format('d/m/Y H:i') }} · Última actualización: {{ $product->updated_at->format('d/m/Y H:i') }}</div>
    <button type="button" class="btn btn-outline-danger mt-4" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-url="{{ route('productos.destroy', $product) }}" data-product-name="{{ $product->name }}">Eliminar producto</button>
</div></div>
@include('products.delete-modal')
@endsection
