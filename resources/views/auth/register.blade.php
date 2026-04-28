@extends('layouts.auth')

@section('content')

<div class="login-page">
    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                {{-- Cabecera --}}
                <div class="text-center mb-4">
                    <div class="login-logo mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="fw-bold">Crear cuenta en <span class="text-primary">aralin</span></h2>
                    <p class="text-muted">Regístrate para empezar</p>
                </div>

                {{-- Tarjeta --}}
                <div class="login-card">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user text-primary"></i>
                                </span>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Tu nombre"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="nombre@ejemplo.com"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock text-primary"></i>
                                </span>
                                <input
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

                        {{-- Confirm Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock text-primary"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>
                        </div>

                        {{-- Rol --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de cuenta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user-tag text-primary"></i>
                                </span>
                                <select
                                    id="role"
                                    name="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Selecciona tu rol</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Alumno</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Profesor</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div class="mb-3" id="bio-field" style="display: none;">
                            <label class="form-label fw-semibold">Biografía (opcional)</label>
                            <textarea
                                id="bio"
                                name="bio"
                                rows="3"
                                class="form-control @error('bio') is-invalid @enderror"
                                placeholder="Cuéntanos sobre tu experiencia como profesor..."
                            >{{ old('bio') }}</textarea>

                            @error('bio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botón --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Link login --}}
                <p class="text-center text-muted mt-4 small">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                        Inicia sesión
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>

{{-- Script para mostrar bio --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const bioField = document.getElementById('bio-field');

    function toggleBio() {
        bioField.style.display = roleSelect.value === 'teacher' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleBio);

    // Mantener estado al recargar con old()
    toggleBio();
});
</script>

@endsection