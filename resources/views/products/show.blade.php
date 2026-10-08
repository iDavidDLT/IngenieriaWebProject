@extends('layouts.app')
@section('title', $product->name)
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="page-heading"><div><span class="eyebrow">FICHA DEL PRODUCTO</span><h1 class="page-title">{{ $product->name }}</h1><p class="page-subtitle">Información, disponibilidad y datos de tu registro.</p></div><div class="heading-actions"><a class="btn btn-primary" href="{{ route('productos.edit', $product) }}">Editar producto</a><button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-url="{{ route('productos.destroy', $product) }}" data-product-name="{{ $product->name }}">Eliminar</button></div></div>
<div class="detail-layout">
    <div class="detail-visual"><div><span class="banner-kicker">REFERENCIA DE CATÁLOGO</span><div class="mt-3"><code class="sku">{{ $product->sku }}</code></div></div><img src="{{ asset('images/industrial-wire.svg') }}" alt="" aria-hidden="true" width="520" height="285"><small>Ideal Alambrec · Gestión de inventario</small></div>
    <section class="panel">
        <div class="detail-metrics"><div><span class="metric-label">PRECIO / USD</span><span class="metric-value">$ {{ number_format((float) $product->price, 2) }}</span></div><div><span class="metric-label">EXISTENCIAS</span><div class="metric-value">{{ number_format($product->stock) }} <span class="fs-6 fw-normal text-secondary">unidades</span></div><span class="stock-pill mt-2 {{ $product->stock <= 5 ? 'stock-low' : 'stock-good' }}">{{ $product->stock <= 5 ? 'Stock bajo' : 'Disponible' }}</span></div></div>
        <div class="detail-description"><h2>Descripción del producto</h2><p class="description mb-0">{{ $product->description ?: 'Sin descripción registrada.' }}</p></div>
        <div class="detail-dates"><span>Creado: {{ $product->created_at->format('d/m/Y H:i') }}</span><span>Última actualización: {{ $product->updated_at->format('d/m/Y H:i') }}</span></div>
    </section>
</div>
@include('products.delete-modal')
@endsection
