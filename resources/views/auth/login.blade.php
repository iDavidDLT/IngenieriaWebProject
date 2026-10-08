@extends('layouts.app')
@section('title', 'Iniciar sesión')
@section('content')
<div class="row g-4 g-lg-5 align-items-center login-layout">
    <section class="col-lg-6">
        <span class="eyebrow">CONTROL DE INVENTARIO</span>
        <h1 class="display-5 fw-bold mt-3">Todo tu inventario.<br><span class="text-accent">En un solo lugar.</span></h1>
        <p class="lead text-secondary mt-3">Consulta tus productos, actualiza existencias y mantén la información al día.</p>
        <div class="d-flex gap-3 mt-4 align-items-center">
            <span class="feature-symbol" aria-hidden="true">✓</span>
            <span>Acceso privado para usuarios autorizados</span>
        </div>
        <div class="d-flex gap-3 mt-3 align-items-center">
            <span class="feature-symbol" aria-hidden="true">▦</span>
            <span>Productos, precios y cantidades organizados</span>
        </div>
        <div class="demo-note mt-4">
            <strong>Cuenta para la demostración académica</strong>
            <div class="mt-2">Usuario: <code>admin</code></div>
            <div>Contraseña: <code>IngenieriaWeb2026!</code></div>
        </div>
    </section>
    <section class="col-lg-5 offset-lg-1">
        <div class="card border-0 shadow-sm p-3 p-sm-4">
            <div class="card-body">
                <span class="eyebrow">BIENVENIDO</span>
                <h2 class="h3 fw-bold mt-2">Iniciar sesión</h2>
                <p class="text-secondary mb-4">Ingresa tus credenciales para continuar.</p>
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input id="username" name="username" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" value="{{ old('username') }}" autocomplete="username" required maxlength="50" autofocus @error('username') aria-describedby="username-error" @enderror>
                        @error('username') <div id="username-error" class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" name="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" autocomplete="current-password" required maxlength="255" @error('password') aria-describedby="password-error" @enderror>
                        @error('password') <div id="password-error" class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-primary btn-lg w-100" type="submit">Entrar al inventario <span aria-hidden="true">→</span></button>
                </form>
                <p class="small text-secondary mt-4 mb-0">Necesitas iniciar sesión para acceder a los productos.</p>
            </div>
        </div>
    </section>
</div>
@endsection
