@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- CABECERA --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Editar perfil</h3>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>

            {{-- ERRORES --}}
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- FOTO DE PERFIL --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Foto de perfil</h6>
                    <div class="d-flex align-items-center gap-4">
                        <div id="foto-preview">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     class="rounded-circle" width="80" height="80" alt="Foto perfil">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                     style="width:80px;height:80px;font-size:1.8rem">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="profile_photo" class="btn btn-outline-primary btn-sm mb-2">
                                <i class="fas fa-camera me-1"></i>Cambiar foto
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo"
                                   class="d-none" accept="image/*">
                            @if($user->profile_photo)
                                <form method="POST" action="{{ route('profile.remove-photo') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm ms-1">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            @endif
                            <p class="text-muted small mb-0 mt-1">JPG, PNG o GIF. Máximo 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN BÁSICA --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Información básica</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold small">Nombre completo</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">Correo electrónico</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="bio" class="form-label fw-semibold small">
                                Biografía <span class="text-muted fw-normal">(opcional)</span>
                            </label>
                            <textarea id="bio" name="bio" class="form-control" rows="3"
                                      placeholder="Cuéntanos sobre ti...">{{ old('bio', $user->bio) }}</textarea>
                            <div class="form-text">Máximo 1000 caracteres.</div>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- CAMBIAR CONTRASEÑA --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-1">Cambiar contraseña</h6>
                    <p class="text-muted small mb-3">Deja estos campos en blanco si no quieres cambiarla.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Contraseña actual</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="••••••••">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Nueva contraseña</label>
                            <input type="password" name="new_password"
                                   class="form-control @error('new_password') is-invalid @enderror"
                                   placeholder="••••••••">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Confirmar nueva contraseña</label>
                            <input type="password" name="new_password_confirmation"
                                   class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                {{-- ROL Y ESTADO (solo lectura) --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Información de la cuenta</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Rol</div>
                            <span class="badge
                                @switch($user->role)
                                    @case('student') bg-success @break
                                    @case('teacher') bg-primary @break
                                    @case('admin')   bg-danger  @break
                                @endswitch">
                                @switch($user->role)
                                    @case('student') Alumno        @break
                                    @case('teacher') Profesor      @break
                                    @case('admin')   Administrador @break
                                @endswitch
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Estado</div>
                            <span class="badge {{ $user->is_blocked ? 'bg-danger' : 'bg-success' }}">
                                {{ $user->is_blocked ? 'Bloqueada' : 'Activa' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fas fa-save me-2"></i>Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview').innerHTML =
                `<img src="${e.target.result}" class="rounded-circle" width="80" height="80" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection