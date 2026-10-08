@extends('layouts.app')
@section('title', 'Inventario de productos')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">CENTRO DE OPERACIONES</span><h1 class="page-title">Inventario de productos</h1><p class="page-subtitle">Tu catálogo y tus existencias, siempre a la vista.</p></div>
    <div class="heading-actions"><a href="{{ route('activity.index') }}" class="btn btn-outline-secondary">Ver historial</a><a href="{{ route('productos.create') }}" class="btn btn-primary">＋ Nuevo producto</a></div>
</div>
<section class="inventory-banner" aria-label="Resumen de inventario">
    <div><span class="banner-kicker">GESTIÓN QUE CONECTA</span><h2>Cada producto cuenta.</h2><p>Consulta, organiza y actualiza los materiales de tu operación.</p></div>
    <div class="banner-number"><strong>{{ $totalProducts }}</strong><span>productos en tu catálogo</span></div>
</section>
<div class="stats-grid">
    @foreach([['Productos registrados', $totalProducts, 'Referencias en tu catálogo', '▦'], ['Unidades disponibles', $totalUnits, 'Existencias de tu cuenta', '▤'], ['Productos con stock bajo', $lowStock, '5 unidades o menos', '↘']] as [$label, $value, $caption, $icon])
    <div class="stat-card"><div class="stat-top"><span class="stat-label">{{ $label }}</span><span class="stat-icon {{ $loop->last ? 'stat-icon-warning' : '' }}" aria-hidden="true">{{ $icon }}</span></div><div class="stat-value">{{ number_format($value) }}</div><div class="stat-caption">{{ $caption }}</div></div>
    @endforeach
</div>
<section class="panel" aria-labelledby="catalog-title">
    <div class="panel-heading"><div><h2 id="catalog-title">Catálogo de productos</h2><p>Información y acciones de tus registros.</p></div><span class="result-count">{{ $products->total() }} resultado(s)</span></div>
    <form action="{{ route('productos.index') }}" method="GET" class="search-form row g-2 align-items-end m-0">
        <div class="col-sm ps-0"><label class="form-label" for="q">Buscar por nombre o código SKU</label><input id="q" name="q" class="form-control" value="{{ $search }}" placeholder="Ej. alambre galvanizado o ALM-001" maxlength="100">@error('q') <span class="text-danger small">{{ $message }}</span> @enderror</div>
        <div class="col-sm-auto px-0"><button class="btn btn-primary" type="submit">Buscar</button>@if($search !== '') <a class="btn btn-outline-secondary" href="{{ route('productos.index') }}">Limpiar</a> @endif</div>
    </form>
    <p class="d-sm-none small text-secondary px-3 pt-3 mb-0">Desliza la tabla para ver todos los datos y acciones →</p>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Tabla de productos, desplazable horizontalmente">
        <table class="table align-middle mb-0 inventory-table">
            <caption class="visually-hidden">Productos del inventario con precio, existencias y acciones</caption>
            <thead><tr><th scope="col">Producto</th><th scope="col">Código SKU</th><th scope="col">Precio / USD</th><th scope="col">Existencias</th><th scope="col" class="text-end">Acciones</th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td><div class="product-cell"><span class="product-emblem" aria-hidden="true">▧</span><div><a class="product-link" href="{{ route('productos.show', $product) }}">{{ $product->name }}</a><div class="product-meta">Registrado el {{ $product->created_at->format('d/m/Y') }}</div></div></div></td>
                    <td><code class="sku">{{ $product->sku }}</code></td>
                    <td class="text-nowrap fw-semibold">$ {{ number_format((float) $product->price, 2) }}</td>
                    <td><span class="stock-pill {{ $product->stock <= 5 ? 'stock-low' : 'stock-good' }}">{{ $product->stock }} unidades</span></td>
                    <td><div class="table-actions"><a href="{{ route('productos.show', $product) }}" class="btn btn-sm btn-outline-secondary" aria-label="Ver {{ $product->name }}">Ver</a><a href="{{ route('productos.edit', $product) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $product->name }}">Editar</a><button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-url="{{ route('productos.destroy', $product) }}" data-product-name="{{ $product->name }}" aria-label="Eliminar {{ $product->name }}">Eliminar</button></div></td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state"><span class="empty-symbol" aria-hidden="true">▦</span><h2>{{ $search !== '' ? 'No encontramos productos' : 'Tu inventario está vacío' }}</h2><p>{{ $search !== '' ? 'Prueba con otro nombre o código SKU.' : 'Registra tu primer producto para comenzar a organizar tus existencias.' }}</p>@if($search === '') <a class="btn btn-primary" href="{{ route('productos.create') }}">Crear producto</a> @endif</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer"><span>{{ $products->total() }} resultado(s) · {{ $products->count() }} en esta página</span>{{ $products->links('pagination::bootstrap-5') }}</div>
</section>
@include('products.delete-modal')
@endsection
