@extends('layouts.app')
@section('title', 'Productos')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <span class="eyebrow">TU ESPACIO DE TRABAJO</span>
        <h1 class="fw-bold mt-2 mb-1">Inventario de productos</h1>
        <p class="text-secondary mb-0">Gestiona los productos y las existencias de tu catálogo.</p>
    </div>
    <div class="d-flex flex-wrap gap-2"><a href="{{ route('activity.index') }}" class="btn btn-outline-secondary px-4 py-2">Historial</a><a href="{{ route('productos.create') }}" class="btn btn-primary px-4 py-2">+ Nuevo producto</a></div>
</div>
<div class="row g-3 mb-4">
    @foreach([['Productos registrados', $totalProducts, 'Catálogo completo'], ['Unidades disponibles', $totalUnits, 'Existencias en inventario'], ['Productos con stock bajo', $lowStock, '5 unidades o menos']] as [$label, $value, $caption])
        <div class="col-md-4">
            <div class="card border-0 stat-card h-100"><div class="card-body p-4">
                <p class="text-secondary mb-2">{{ $label }}</p>
                <div class="h2 fw-bold mb-1">{{ $value }}</div>
                <span class="small text-secondary">{{ $caption }}</span>
            </div></div>
        </div>
    @endforeach
</div>
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white p-4 border-bottom">
        <form action="{{ route('productos.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-sm">
                <label class="form-label small text-secondary" for="q">Buscar por nombre o código SKU</label>
                <input id="q" name="q" class="form-control" value="{{ $search }}" placeholder="Ej. teclado o TEC-001" maxlength="100">
                @error('q') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-auto">
                <button class="btn btn-primary" type="submit">Buscar</button>
                @if($search !== '') <a class="btn btn-outline-secondary" href="{{ route('productos.index') }}">Limpiar</a> @endif
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0 inventory-table">
            <caption class="visually-hidden">Productos del inventario con precio, existencias y acciones</caption>
            <thead><tr><th scope="col">Producto</th><th scope="col">SKU</th><th scope="col">Precio</th><th scope="col">Existencias</th><th scope="col" class="text-end">Acciones</th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td><a class="product-link fw-semibold" href="{{ route('productos.show', $product) }}">{{ $product->name }}</a><div class="small text-secondary">Registrado el {{ $product->created_at->format('d/m/Y') }}</div></td>
                    <td><code class="sku">{{ $product->sku }}</code></td>
                    <td class="text-nowrap">$ {{ number_format((float) $product->price, 2) }}</td>
                    <td><span class="badge {{ $product->stock <= 5 ? 'text-bg-warning' : 'stock-good' }}">{{ $product->stock }} unidades</span></td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('productos.show', $product) }}" class="btn btn-sm btn-outline-secondary" aria-label="Ver {{ $product->name }}">Ver</a>
                            <a href="{{ route('productos.edit', $product) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $product->name }}">Editar</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-url="{{ route('productos.destroy', $product) }}" data-product-name="{{ $product->name }}" aria-label="Eliminar {{ $product->name }}">Eliminar</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-5">
                    <h2 class="h5">{{ $search !== '' ? 'No encontramos productos' : 'Tu inventario está vacío' }}</h2>
                    <p class="text-secondary">{{ $search !== '' ? 'Prueba con otro nombre o SKU.' : 'Crea tu primer producto para comenzar.' }}</p>
                    @if($search === '') <a class="btn btn-primary" href="{{ route('productos.create') }}">Crear producto</a> @endif
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white p-4">
        <div class="small text-secondary mb-2">{{ $products->total() }} resultado(s)</div>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@include('products.delete-modal')
@endsection
