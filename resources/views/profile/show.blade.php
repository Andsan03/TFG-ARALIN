@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Mi Perfil
                    </h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Foto de Perfil -->
                        <div class="col-md-4 text-center">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     class="rounded-circle mb-3" width="150" height="150" alt="Profile">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                            
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <span class="badge 
                                @switch($user->role)
                                    @case('student') bg-success @break
                                    @case('teacher') bg-primary @break
                                    @case('admin') bg-danger @break
                                @endswitch
                            ">
                                @switch($user->role)
                                    @case('student') Alumno @break
                                    @case('teacher') Profesor @break
                                    @case('admin') Administrador @break
                                @endswitch
                            </span>
                            
                            <p class="text-muted small mt-2">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>

                        <!-- Información del Perfil -->
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nombre:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Rol:</th>
                                    <td>
                                        <span class="badge 
                                            @switch($user->role)
                                                @case('student') bg-success @break
                                                @case('teacher') bg-primary @break
                                                @case('admin') bg-danger @break
                                            @endswitch
                                        ">
                                            @switch($user->role)
                                                @case('student') Alumno @break
                                                @case('teacher') Profesor @break
                                                @case('admin') Administrador @break
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Biografía:</th>
                                    <td>{{ $user->bio ?? 'No especificada' }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($user->is_blocked)
                                            <span class="badge bg-danger">Bloqueado</span>
                                        @else
                                            <span class="badge bg-success">Activo</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Estadísticas según rol -->
                            @if($user->role === 'student')
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3">Estadísticas como Alumno</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h4 class="text-primary">{{ $user->bookings->count() }}</h4>
                                                    <small class="text-muted">Total Reservas</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h4 class="text-success">{{ $user->favoriteTeachers->count() }}</h4>
                                                    <small class="text-muted">Profesores Favoritos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($user->role === 'teacher')
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3">Estadísticas como Profesor</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h4 class="text-primary">{{ $user->classes->count() }}</h4>
                                                    <small class="text-muted">Total Clases</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h4 class="text-warning">{{ $user->reviewsReceived->count() }}</h4>
                                                    <small class="text-muted">Reseñas Recibidas</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
