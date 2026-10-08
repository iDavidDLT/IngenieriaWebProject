<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#132b36">
    <title>@yield('title', 'Inventario') · Ingeniería Web</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-nav">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <span class="brand-mark">IW</span>
            <span>Ingeniería Web <small class="d-block brand-caption">Gestión de inventario</small></span>
        </a>
        @auth
            <div class="d-flex align-items-center gap-3 ms-auto">
                <span class="text-white-50 d-none d-sm-inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Cerrar sesión</button>
                </form>
            </div>
        @endauth
    </div>
</nav>
<main class="container py-4 py-lg-5">
    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center justify-content-between" role="status">
            <span>{{ session('status') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar aviso"></button>
        </div>
    @endif
    @yield('content')
</main>
<footer class="container pb-4 text-secondary small">Ingeniería Web · Aplicación académica de inventario</footer>
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
