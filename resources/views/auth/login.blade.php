@extends('layouts.app')
@section('title', 'Iniciar sesión')
@section('content')
<div class="login-layout">
    <section class="login-story" aria-labelledby="login-story-title">
        <span class="eyebrow">CONTROL QUE IMPULSA TU OPERACIÓN</span>
        <h1 id="login-story-title">La fuerza de<br>un inventario<br><span>bien conectado.</span></h1>
        <p>Organiza tu catálogo, consulta existencias y mantén cada producto bajo control.</p>
        <img class="industrial-art" src="{{ asset('images/industrial-wire.svg') }}" alt="" aria-hidden="true" width="520" height="285">
        <div class="story-sectors" aria-label="Sectores de referencia"><span>CONSTRUCCIÓN</span><span>AGRICULTURA</span><span>INDUSTRIA</span><span>MINERÍA</span></div>
    </section>
    <section class="login-entry" aria-labelledby="login-title">
        <div class="login-entry-inner">
            <span class="section-kicker">PORTAL DE INVENTARIO</span>
            <h2 id="login-title">Iniciar sesión</h2>
            <p class="intro">Bienvenido. Ingresa tus credenciales para acceder a tu espacio de trabajo.</p>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="username" class="form-label">Usuario</label>
                    <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Tu nombre de usuario" autocomplete="username" required maxlength="50" autofocus @error('username') aria-describedby="username-error" aria-invalid="true" @enderror>
                    @error('username') <div id="username-error" class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu contraseña" autocomplete="current-password" required maxlength="72" @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
                        <button class="password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Mostrar contraseña" aria-pressed="false">Mostrar</button>
                    </div>
                    @error('password') <div id="password-error" class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary login-submit" type="submit"><span>Entrar al inventario</span><span aria-hidden="true">→</span></button>
                <p class="login-help">Acceso exclusivo para usuarios con una cuenta habilitada.</p>
            </form>
        </div>
    </section>
</div>
@endsection
