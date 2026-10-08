<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductAudit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Product::class);
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($filters['q'] ?? ''));
        $inventory = Product::where('user_id', $request->user()->id);
        $query = (clone $inventory)->when($search !== '', function (Builder $query) use ($search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')->orWhere('sku', 'like', '%'.$search.'%');
            });
        });

        return view('products.index', [
            'products' => $query->orderByDesc('id')->paginate(8)->withQueryString(),
            'search' => $search,
            'totalProducts' => (clone $inventory)->count(),
            'totalUnits' => (clone $inventory)->sum('stock'),
            'lowStock' => (clone $inventory)->where('stock', '<=', 5)->count(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('products.create', ['product' => new Product]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request): Product {
            $product = $request->user()->products()->create($request->validated());
            $this->recordAudit('create', $product, $request->user()->id);

            return $product;
        });

        return redirect()->route('productos.show', $product)->with('status', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);
        DB::transaction(function () use ($request, $product): void {
            $product->update($request->validated());
            $this->recordAudit('update', $product, $request->user()->id);
        });

        return redirect()->route('productos.show', $product)->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);
        DB::transaction(function () use ($request, $product): void {
            $this->recordAudit('delete', $product, $request->user()->id);
            $product->delete();
        });

        return redirect()->route('productos.index')->with('status', 'Producto eliminado correctamente.');
    }

    private function recordAudit(string $action, Product $product, int $userId): void
    {
        ProductAudit::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'action' => $action,
            'sku' => $product->sku,
            'product_name' => $product->name,
        ]);
    }
}
