@if(Auth::check() && Auth::user()->hasRole('ADMINISTRADOR'))
    <li class="side-menus {{ Request::is('*') ? 'active' : '' }}">
        <a class="nav-link" href="/home">
            <i class="fas fa-building"></i><span>Dashboard</span>
        </a>
        <a class="nav-link" href="/usuarios">
            <i class="fas fa-users"></i><span>Usuarios</span>
        </a>
        <a class="nav-link" href="/roles">
            <i class="fas fa-user-lock"></i><span>Roles</span>
        </a>
    </li>
@endif

<!-- Equipos y Movimientos Sucursal para Gestor TI o Administrador -->
@if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-dice-d6"></i> <span>Equipos</span>
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('equipos.create') }}">Nuevo</a>
            <a class="dropdown-item" href="/equipos">Listar inventario</a>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-truck"></i> <span>Movimientos Sucursal</span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-header">Sucursal</div>
            <a href="{{ route('movimientos.create') }}"><i class="fas fa-plus"></i> Nueva Salida</a>
            <a class="dropdown-item" href="/movimientos"><i class="fas fa-list"></i> Listar Salida</a>
        </div>
    </li>
@endif

<!-- Movimientos Sucursal solo para Secretaria -->
@if(Auth::check() && (Auth::user()->hasRole('GERENTE') || Auth::user()->hasRole('SECRETARIA')))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-truck"></i> <span>Movimientos Sucursal</span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-header">Sucursal</div>
            <a href="{{ route('movimientos.create') }}"><i class="fas fa-plus"></i> Nueva Salida</a>
            <a class="dropdown-item" href="/movimientos"><i class="fas fa-list"></i> Listar Salida</a>
        </div>
    </li>
@endif




<!-- @if(Auth::check() && Auth::user()->hasRole('Administrador'))
@endif -->