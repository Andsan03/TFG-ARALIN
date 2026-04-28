<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aralin') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (iconos) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- CSS propio de Aralin -->
    <link rel="stylesheet" href="{{ asset('css/aralin.css') }}">
</head>
<body>
    <div id="app">

        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap me-2"></i>Aralin
                </a>
                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    {{-- Links izquierda según rol --}}
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->role === 'student')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('student.search') ? 'active' : '' }}"
                                       href="{{ route('student.search') }}">
                                        <i class="fas fa-search me-1"></i>Buscar Clases
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                                       href="{{ route('student.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('student.bookings*') ? 'active' : '' }}"
                                       href="{{ route('student.bookings') }}">
                                        <i class="fas fa-calendar me-1"></i>Mis Reservas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('student.favorites*') ? 'active' : '' }}"
                                       href="{{ route('student.favorites') }}">
                                        <i class="fas fa-heart me-1"></i>Favoritos
                                    </a>
                                </li>

                            @elseif(Auth::user()->role === 'teacher')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                                       href="{{ route('teacher.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teacher.classes*') ? 'active' : '' }}"
                                       href="{{ route('teacher.classes') }}">
                                        <i class="fas fa-book me-1"></i>Mis Clases
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teacher.bookings') ? 'active' : '' }}"
                                       href="{{ route('teacher.bookings') }}">
                                        <i class="fas fa-calendar me-1"></i>Reservas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teacher.reviews') ? 'active' : '' }}"
                                       href="{{ route('teacher.reviews') }}">
                                        <i class="fas fa-star me-1"></i>Reseñas
                                    </a>
                                </li>

                            @elseif(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                       href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                                       href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users me-1"></i>Usuarios
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                                   href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-1"></i>Mi Perfil
                                </a>
                            </li>
                        @endauth
                    </ul>

                    {{-- Links derecha: login/registro o dropdown usuario --}}
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if(!request()->routeIs('login') && !request()->routeIs('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar sesión
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-primary btn-sm ms-2 px-3" href="{{ route('register') }}">
                                        Registrarse
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center"
                                   href="#" role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                             class="rounded-circle me-2"
                                             width="28" height="28" alt="Foto perfil">
                                    @else
                                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center me-2"
                                             style="width:28px; height:28px;">
                                            <i class="fas fa-user text-white" style="font-size:12px;"></i>
                                        </div>
                                    @endif
                                    {{ Auth::user()->name }}
                                    <span class="badge ms-2
                                        @switch(Auth::user()->role)
                                            @case('student') bg-success @break
                                            @case('teacher') bg-primary @break
                                            @case('admin') bg-danger @break
                                        @endswitch">
                                        @switch(Auth::user()->role)
                                            @case('student') Alumno @break
                                            @case('teacher') Profesor @break
                                            @case('admin') Admin @break
                                        @endswitch
                                    </span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i>Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        {{-- Mensajes de alerta globales --}}
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <main>
            @yield('content')
        </main>

    </div>

    <!-- Bootstrap 5 JS (necesario para dropdown, navbar toggler, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>