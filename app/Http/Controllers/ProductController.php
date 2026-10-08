<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($filters['q'] ?? ''));
        $query = Product::query()->when($search !== '', function (Builder $query) use ($search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')->orWhere('sku', 'like', '%'.$search.'%');
            });
        });

        return view('products.index', [
            'products' => $query->orderByDesc('id')->paginate(8)->withQueryString(),
            'search' => $search,
            'totalProducts' => Product::count(),
            'totalUnits' => Product::sum('stock'),
            'lowStock' => Product::where('stock', '<=', 5)->count(),
        ]);
    }

    public function create(): View
    {
        return view('products.create', ['product' => new Product]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->validated());

        return redirect()->route('productos.show', $product)->with('status', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('productos.show', $product)->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('productos.index')->with('status', 'Producto eliminado correctamente.');
    }
}
