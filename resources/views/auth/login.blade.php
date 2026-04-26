@extends('layouts.app')

@section('content')

<div class="login-page">
    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                {{-- Cabecera --}}
                <div class="text-center mb-4">
                    <div class="login-logo mb-3">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2 class="fw-bold">Bienvenido a <span class="text-primary">aralin</span></h2>
                    <p class="text-muted">Inicia sesión para continuar</p>
                </div>

                {{-- Tarjeta --}}
                <div class="login-card">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="nombre@ejemplo.com"
                                    required autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Contraseña --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock text-primary"></i>
                                </span>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Recuérdame + olvidaste contraseña --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember">
                                    Recuérdame
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        {{-- Botón --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Enlace registro --}}
                <p class="text-center text-muted mt-4 small">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                        Regístrate aquí
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>

@endsection