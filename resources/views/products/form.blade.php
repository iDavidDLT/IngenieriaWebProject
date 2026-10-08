<div class="form-panel-body">
    @if($errors->any())
        <div class="alert alert-danger small" role="alert">Revisa los campos señalados antes de guardar.</div>
    @endif
    <h2 class="form-section-title"><span class="section-number">01</span> Identificación</h2>
    <div class="row g-4">
        <div class="col-md-8">
            <label for="name" class="form-label">Nombre del producto <span class="text-danger">*</span></label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" placeholder="Ej. Alambre galvanizado" maxlength="100" required autofocus @error('name') aria-describedby="name-error" aria-invalid="true" @enderror>
            @error('name') <div id="name-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label for="sku" class="form-label">Código SKU <span class="text-danger">*</span></label>
            <input id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="ALM-001" maxlength="30" required aria-describedby="sku-help @error('sku') sku-error @enderror" @error('sku') aria-invalid="true" @enderror>
            @error('sku') <div id="sku-error" class="invalid-feedback">{{ $message }}</div> @enderror
            <div id="sku-help" class="form-text">Código único de tu producto.</div>
        </div>
    </div>
    <div class="field-divider"></div>
    <h2 class="form-section-title"><span class="section-number">02</span> Precio y existencias</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <label for="price" class="form-label">Precio (USD) <span class="text-danger">*</span></label>
            <input id="price" name="price" type="number" step="0.01" min="0" max="9999999.99" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" placeholder="0.00" required @error('price') aria-describedby="price-error" aria-invalid="true" @enderror>
            @error('price') <div id="price-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label for="stock" class="form-label">Cantidad disponible <span class="text-danger">*</span></label>
            <input id="stock" name="stock" type="number" step="1" min="0" max="1000000" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" required @error('stock') aria-describedby="stock-error" aria-invalid="true" @enderror>
            @error('stock') <div id="stock-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="field-divider"></div>
    <h2 class="form-section-title"><span class="section-number">03</span> Información adicional</h2>
    <label for="description" class="form-label">Descripción <span class="text-secondary fw-normal">(opcional)</span></label>
    <textarea id="description" name="description" rows="4" maxlength="1000" placeholder="Características, presentación o información útil del producto." class="form-control @error('description') is-invalid @enderror" @error('description') aria-describedby="description-error" aria-invalid="true" @enderror>{{ old('description', $product->description) }}</textarea>
    @error('description') <div id="description-error" class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-actions"><button type="submit" class="btn btn-primary">{{ $submitLabel }} <span aria-hidden="true">→</span></button><a class="btn btn-outline-secondary" href="{{ route('productos.index') }}">Cancelar</a></div>
