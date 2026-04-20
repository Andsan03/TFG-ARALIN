@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Editar Perfil
                    </h5>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Foto de Perfil -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         id="preview-image" class="rounded-circle" width="120" height="120" alt="Profile">
                                @else
                                    <div id="preview-image" class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 120px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <label for="profile_photo" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-camera me-1"></i>Cambiar Foto
                                    </label>
                                    <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                    
                                    @if($user->profile_photo)
                                        <form method="POST" action="{{ route('profile.remove-photo') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-2">
                                                <i class="fas fa-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Información Básica -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre Completo</label>
                                <input type="text" id="name" name="name" class="form-control" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Biografía -->
                        <div class="mb-4">
                            <label for="bio" class="form-label">Biografía</label>
                            <textarea id="bio" name="bio" class="form-control" rows="4" 
                                      placeholder="Cuéntanos sobre ti...">{{ old('bio', $user->bio) }}</textarea>
                            <small class="form-text text-muted">Máximo 1000 caracteres</small>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cambio de Contraseña -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">Deja estos campos en blanco si no quieres cambiar tu contraseña.</p>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="current_password" class="form-label">Contraseña Actual</label>
                                        <input type="password" id="current_password" name="current_password" 
                                               class="form-control @error('current_password') is-invalid @enderror">
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                                        <input type="password" id="new_password" name="new_password" 
                                               class="form-control @error('new_password') is-invalid @enderror">
                                        @error('new_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Rol -->
                        <div class="card bg-info text-white mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Tu Rol Actual:</h6>
                                        <span class="badge bg-white text-info fs-6">
                                            @switch($user->role)
                                                @case('student') Alumno @break
                                                @case('teacher') Profesor @break
                                                @case('admin') Administrador @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Estado de Cuenta:</h6>
                                        <span class="badge 
                                            @if($user->is_blocked) bg-danger @else bg-success @endif
                                            fs-6">
                                            @if($user->is_blocked) Bloqueada @else Activa @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_photo');
    const previewImage = document.getElementById('preview-image');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="120" height="120" alt="Preview">`;
            };
            
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection
