<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#29478d">
    <title>@yield('title', 'Inventario') · Ideal Alambrec</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="@auth workspace-page @else access-page @endauth">
<a class="skip-link" href="#main-content">Saltar al contenido</a>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="{{ url('/') }}" aria-label="Ideal Alambrec, inicio">
            <span class="brand-symbol" aria-hidden="true"><span></span><span></span><span></span></span>
            <span class="brand-name">ideal<span>alambrec</span><small>GESTIÓN DE INVENTARIO</small></span>
        </a>
        @auth
        <div class="header-account">
            <span class="account-avatar" aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
            <span class="account-name">{{ auth()->user()->name }}<small>{{ auth()->user()->username }}</small></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-exit" type="submit">Cerrar sesión <span aria-hidden="true">↗</span></button>
            </form>
        </div>
        @else
        <span class="header-label"><span class="status-dot"></span> Portal de inventario</span>
        @endauth
    </div>
</header>
@auth
<div class="workspace-shell">
    <aside class="workspace-sidebar" aria-label="Navegación del inventario">
        <div class="sidebar-caption">ESPACIO DE TRABAJO</div>
        <nav class="workspace-menu">
            <a class="menu-item {{ request()->routeIs('productos.index', 'productos.show', 'productos.edit') ? 'is-active' : '' }}" href="{{ route('productos.index') }}" @if(request()->routeIs('productos.index', 'productos.show', 'productos.edit')) aria-current="page" @endif><span class="menu-icon" aria-hidden="true">▦</span> Inventario</a>
            <a class="menu-item {{ request()->routeIs('productos.create') ? 'is-active' : '' }}" href="{{ route('productos.create') }}" @if(request()->routeIs('productos.create')) aria-current="page" @endif><span class="menu-icon" aria-hidden="true">＋</span> Nuevo producto</a>
            <a class="menu-item {{ request()->routeIs('activity.index') ? 'is-active' : '' }}" href="{{ route('activity.index') }}" @if(request()->routeIs('activity.index')) aria-current="page" @endif><span class="menu-icon" aria-hidden="true">◷</span> Historial</a>
        </nav>
        <div class="sidebar-note"><span class="sidebar-note-mark">PRODUCTOS & EXISTENCIAS</span><strong>Tu operación,<br>bien organizada.</strong><p>Consulta y administra tu catálogo en un solo lugar.</p></div>
        <div class="sidebar-bottom"><span class="status-dot"></span> Sesión activa</div>
    </aside>
    <div class="workspace-body">
@endauth
<main id="main-content" class="@auth workspace-content @else access-content @endauth" tabindex="-1">
    @if(session('status'))
        <div class="alert alert-success status-alert d-flex align-items-center justify-content-between" role="status"><span>{{ session('status') }}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar aviso"></button></div>
    @endif
    @yield('content')
</main>
<footer class="site-footer"><span>Ideal Alambrec <span class="footer-divider">/</span> Inventario</span><span>Ingeniería Web · Proyecto académico</span></footer>
@auth
    </div>
</div>
@endauth
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
