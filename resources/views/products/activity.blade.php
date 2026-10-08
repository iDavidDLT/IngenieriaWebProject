@extends('layouts.app')
@section('title', 'Historial de actividad')
@section('content')
<a href="{{ route('productos.index') }}" class="back-link">← Volver al inventario</a>
<div class="mt-3 mb-4">
    <span class="eyebrow">TRAZABILIDAD</span>
    <h1 class="fw-bold mt-2">Historial de actividad</h1>
    <p class="text-secondary">Consulta cuándo creaste, editaste o eliminaste tus productos.</p>
</div>
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table inventory-table align-middle mb-0">
            <caption class="visually-hidden">Historial de operaciones de tu cuenta</caption>
            <thead><tr><th scope="col">Fecha</th><th scope="col">Usuario</th><th scope="col">Operación</th><th scope="col">Producto</th><th scope="col">SKU</th></tr></thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td class="text-nowrap">{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $activity->user->username }}</td>
                        <td><span class="badge text-bg-secondary">{{ ['create' => 'Creación', 'update' => 'Edición', 'delete' => 'Eliminación'][$activity->action] ?? $activity->action }}</span></td>
                        <td>{{ $activity->product_name }}</td>
                        <td><code class="sku">{{ $activity->sku }}</code></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5">Todavía no hay operaciones registradas para tu cuenta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white p-4">{{ $activities->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
