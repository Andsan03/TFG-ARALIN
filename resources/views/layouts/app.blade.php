<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap me-2"></i>ARALIN
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar - Navegación por Rol -->
                    <ul class="navbar-nav me-auto">
                        @guest
                            <!-- Links para usuarios no autenticados - Solo login/registro -->
                        @else
                            <!-- Links para usuarios autenticados - según rol -->
                            @auth
                                @if(Auth::user()->role === 'student')
                                    <!-- Navegación para Alumnos -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                                           href="{{ route('student.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('student.search') ? 'active' : '' }}" 
                                           href="{{ route('student.search') }}">
                                            <i class="fas fa-search me-1"></i>Buscar Clases
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
                                    <!-- Navegación para Profesores -->
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
                                    <!-- Navegación para Administradores -->
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
                                
                                <!-- Link común para todos los roles -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}" 
                                       href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-1"></i>Mi Perfil
                                    </a>
                                </li>
                            @endauth
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i>{{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                             class="rounded-circle me-2" width="24" height="24" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center me-2" 
                                             style="width: 24px; height: 24px;">
                                            <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                        </div>
                                    @endif
                                    {{ Auth::user()->name }}
                                    <span class="badge 
                                        @switch(Auth::user()->role)
                                            @case('student') bg-success @break
                                            @case('teacher') bg-primary @break
                                            @case('admin') bg-danger @break
                                        @endswitch
                                    ms-2">
                                        @switch(Auth::user()->role)
                                            @case('student') Alumno @break
                                            @case('teacher') Profesor @break
                                            @case('admin') Admin @break
                                        @endswitch
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Perfil -->
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Mi Perfil
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <!-- Dashboard según rol -->
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <!-- Logout -->
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
