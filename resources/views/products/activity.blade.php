@extends('layouts.app')
@section('title', 'Historial de actividad')
@section('content')
<div class="page-heading"><div><span class="eyebrow">TRAZABILIDAD DE TU OPERACIÓN</span><h1 class="page-title">Historial de actividad</h1><p class="page-subtitle">Consulta cuándo creaste, editaste o eliminaste tus productos.</p></div><a class="btn btn-outline-secondary" href="{{ route('productos.index') }}">Volver al inventario</a></div>
<div class="audit-intro"><span class="audit-symbol" aria-hidden="true">◷</span><span>Cada cambio deja un registro. Aquí puedes consultar las operaciones realizadas desde tu cuenta, incluso después de eliminar un producto.</span></div>
<section class="panel" aria-labelledby="activity-title">
    <div class="panel-heading"><div><h2 id="activity-title">Registro de operaciones</h2><p>Las actividades más recientes aparecen primero.</p></div><span class="result-count">{{ $activities->total() }} registro(s)</span></div>
    <p class="d-sm-none small text-secondary px-3 pt-3 mb-0">Desliza la tabla para consultar todos los datos →</p>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Tabla de historial, desplazable horizontalmente">
        <table class="table inventory-table align-middle mb-0">
            <caption class="visually-hidden">Historial de operaciones de tu cuenta</caption>
            <thead><tr><th scope="col">Fecha y hora</th><th scope="col">Usuario</th><th scope="col">Operación</th><th scope="col">Producto</th><th scope="col">Código SKU</th></tr></thead>
            <tbody>
            @forelse($activities as $activity)
                <tr><td class="text-nowrap"><span class="fw-semibold">{{ $activity->created_at->format('d/m/Y') }}</span><div class="product-meta">{{ $activity->created_at->format('H:i:s') }}</div></td><td>{{ $activity->user->username }}</td><td><span class="activity-badge activity-{{ in_array($activity->action, ['create', 'update', 'delete'], true) ? $activity->action : 'update' }}">{{ ['create' => 'Creación', 'update' => 'Edición', 'delete' => 'Eliminación'][$activity->action] ?? $activity->action }}</span></td><td class="fw-semibold">{{ $activity->product_name }}</td><td><code class="sku">{{ $activity->sku }}</code></td></tr>
            @empty
                <tr><td colspan="5"><div class="empty-state"><span class="empty-symbol" aria-hidden="true">◷</span><h2>Tu historial comienza aquí</h2><p>Todavía no hay operaciones registradas para tu cuenta.</p><a class="btn btn-primary" href="{{ route('productos.create') }}">Registrar un producto</a></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer"><span>{{ $activities->total() }} operación(es) registradas</span>{{ $activities->links('pagination::bootstrap-5') }}</div>
</section>
@endsection
