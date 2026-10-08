@extends('layouts.app')
@section('title', 'Editar producto')
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="page-heading"><div><span class="eyebrow">MANTÉN TU CATÁLOGO AL DÍA</span><h1 class="page-title">Editar producto</h1><p class="page-subtitle">Actualiza la información de {{ $product->name }}.</p></div><code class="sku">{{ $product->sku }}</code></div>
<div class="form-layout">
    <section class="panel">
        <div class="panel-heading"><div><h2>Información del producto</h2><p>Revisa los datos antes de guardar tus cambios.</p></div></div>
        <form method="POST" action="{{ route('productos.update', $product) }}">
            @csrf
            @method('PUT')
            @include('products.form', ['submitLabel' => 'Guardar cambios'])
        </form>
    </section>
    @include('products.form-aside')
</div>
@endsection
