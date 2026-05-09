<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aralin') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- CSS Aralin -->
    <link rel="stylesheet" href="{{ asset('css/aralin.css') }}">
</head>
<body>
<div id="app">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-md bg-white border-bottom sticky-top">
        <div class="container">

            {{-- Logo --}}
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap me-2"></i>aralin
            </a>

            {{-- Toggler móvil --}}
            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMain"
                    aria-controls="navbarMain"
                    aria-expanded="false"
                    aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">

                {{-- Links izquierda según rol --}}
                <ul class="navbar-nav me-auto mt-2 mt-md-0">
                    @auth

                        @if(Auth::user()->role === 'student')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('student.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.search') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('student.search') }}">
                                    <i class="fas fa-search me-1"></i>Buscar clases
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.bookings*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('student.bookings') }}">
                                    <i class="fas fa-calendar me-1"></i>Mis reservas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.favorites*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('student.favorites') }}">
                                    <i class="fas fa-heart me-1"></i>Favoritos
                                </a>
                            </li>

                        @elseif(Auth::user()->role === 'teacher')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('teacher.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teacher.classes*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('teacher.classes') }}">
                                    <i class="fas fa-book me-1"></i>Mis clases
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teacher.bookings') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('teacher.bookings') }}">
                                    <i class="fas fa-calendar me-1"></i>Reservas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teacher.reviews') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('teacher.reviews') }}">
                                    <i class="fas fa-star me-1"></i>Reseñas
                                </a>
                            </li>

                        @elseif(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('admin.users') }}">
                                    <i class="fas fa-users me-1"></i>Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.classes*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('admin.classes') }}">
                                    <i class="fas fa-book me-1"></i>Clases
                                </a>
                            </li>
                        @endif

                    @endauth
                </ul>

                {{-- Links derecha --}}
                <ul class="navbar-nav ms-auto align-items-center mt-2 mt-md-0">
                    @guest
                        @if(!request()->routeIs('login') && !request()->routeIs('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    Iniciar sesión
                                </a>
                            </li>
                            <li class="nav-item ms-1">
                                <a class="btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                                    Registrarse
                                </a>
                            </li>
                        @endif
                    @else
                        {{-- Dropdown usuario --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                               href="#" role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                         class="rounded-circle"
                                         width="30" height="30" alt="Foto">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                         style="width:30px;height:30px;font-size:12px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                <span class="badge
                                    @switch(Auth::user()->role)
                                        @case('student') bg-success @break
                                        @case('teacher') bg-primary @break
                                        @case('admin')   bg-danger  @break
                                    @endswitch">
                                    @switch(Auth::user()->role)
                                        @case('student') Alumno   @break
                                        @case('teacher') Profesor @break
                                        @case('admin')   Admin    @break
                                    @endswitch
                                </span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-1">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">{{ Auth::user()->email }}</div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2 text-muted"></i>Mi perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-muted"></i>Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
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

    {{-- ALERTAS GLOBALES --}}
    @if(session('success') || session('error'))
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    {{-- CONTENIDO --}}
    <main>
        @yield('content')
    </main>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>