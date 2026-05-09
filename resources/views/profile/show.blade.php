@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- CABECERA --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Mi perfil</h3>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Editar perfil
                </a>
            </div>

            {{-- TARJETA PRINCIPAL --}}
            <div class="bg-white border rounded-3 overflow-hidden mb-4">

                {{-- Banner + foto --}}
                <div class="profile-banner bg-primary bg-opacity-10" style="height:80px"></div>
                <div class="px-4 pb-4">
                    <div class="d-flex align-items-end justify-content-between" style="margin-top:-40px">
                        <div>
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     class="rounded-circle border-white"
                                     width="80" height="80" alt="Foto perfil">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center border border-white"
                                     style="width:80px;height:80px;font-size:1.8rem">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <span class="badge mb-2
                            @switch($user->role)
                                @case('student') bg-success @break
                                @case('teacher') bg-primary @break
                                @case('admin')   bg-danger  @break
                            @endswitch">
                            @switch($user->role)
                                @case('student') Alumno       @break
                                @case('teacher') Profesor     @break
                                @case('admin')   Administrador @break
                            @endswitch
                        </span>
                    </div>

                    <h4 class="fw-bold mt-2 mb-0">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">
                        Miembro desde {{ $user->created_at->format('d/m/Y') }}
                    </p>

                    @if($user->bio)
                        <p class="text-muted mb-0">{{ $user->bio }}</p>
                    @else
                        <p class="text-muted fst-italic mb-0 small">Sin biografía — <a href="{{ route('profile.edit') }}" class="text-primary text-decoration-none">añadir</a></p>
                    @endif
                </div>
            </div>

            {{-- DATOS --}}
            <div class="bg-white border rounded-3 p-4 mb-4">
                <h6 class="fw-bold mb-3">Información de la cuenta</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Nombre</div>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Correo electrónico</div>
                        <div class="fw-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Rol</div>
                        <span class="badge
                            @switch($user->role)
                                @case('student') bg-success @break
                                @case('teacher') bg-primary @break
                                @case('admin')   bg-danger  @break
                            @endswitch">
                            @switch($user->role)
                                @case('student') Alumno       @break
                                @case('teacher') Profesor     @break
                                @case('admin')   Administrador @break
                            @endswitch
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Estado</div>
                        @if($user->is_blocked)
                            <span class="badge bg-danger">Bloqueado</span>
                        @else
                            <span class="badge bg-success">Activo</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ESTADÍSTICAS SEGÚN ROL --}}
            @if($user->role === 'student')
                <div class="bg-white border rounded-3 p-4">
                    <h6 class="fw-bold mb-3">Estadísticas</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ $user->bookings->count() }}</div>
                                <div class="text-muted small">Reservas totales</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="stat-icon bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ $user->favoriteTeachers->count() }}</div>
                                <div class="text-muted small">Profesores favoritos</div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($user->role === 'teacher')
                <div class="bg-white border rounded-3 p-4">
                    <h6 class="fw-bold mb-3">Estadísticas</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ $user->classes->count() }}</div>
                                <div class="text-muted small">Clases publicadas</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ $user->reviewsReceived->count() }}</div>
                                <div class="text-muted small">Reseñas recibidas</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection