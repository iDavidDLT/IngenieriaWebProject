@if($errors->any())
    <div class="alert alert-danger" role="alert">Revisa los campos señalados antes de guardar.</div>
@endif
<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="form-label">Nombre del producto <span class="text-danger">*</span></label>
        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" maxlength="100" required autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="sku" class="form-label">Código SKU <span class="text-danger">*</span></label>
        <input id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" maxlength="30" required aria-describedby="sku-help">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div id="sku-help" class="form-text">Código único. Ejemplo: TEC-005.</div>
    </div>
    <div class="col-md-6">
        <label for="price" class="form-label">Precio (USD) <span class="text-danger">*</span></label>
        <input id="price" name="price" type="number" step="0.01" min="0" max="9999999.99" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="stock" class="form-label">Cantidad disponible <span class="text-danger">*</span></label>
        <input id="stock" name="stock" type="number" step="1" min="0" max="1000000" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Descripción <span class="text-secondary small">(opcional)</span></label>
        <textarea id="description" name="description" rows="4" maxlength="1000" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="border-top mt-4 pt-4 d-flex flex-wrap gap-2">
    <button type="submit" class="btn btn-primary px-4">{{ $submitLabel }}</button>
    <a class="btn btn-outline-secondary" href="{{ route('productos.index') }}">Cancelar</a>
</div>
