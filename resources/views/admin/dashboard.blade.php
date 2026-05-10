@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Panel de administración</h3>
            <p class="text-muted mb-0">Visión general de la plataforma</p>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                    <i class="fas fa-users"></i>
                </div>
                <div class="fw-bold fs-4">{{ $stats['total_users'] }}</div>
                <div class="text-muted small">Usuarios</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                    <i class="fas fa-book"></i>
                </div>
                <div class="fw-bold fs-4">{{ $stats['total_classes'] }}</div>
                <div class="text-muted small">Clases</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="fw-bold fs-4">{{ $stats['total_bookings'] }}</div>
                <div class="text-muted small">Reservas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info mx-auto mb-2">
                    <i class="fas fa-star"></i>
                </div>
                <div class="fw-bold fs-4">{{ $stats['total_reviews'] }}</div>
                <div class="text-muted small">Reseñas</div>
            </div>
        </div>
    </div>

    {{-- DESGLOSE USUARIOS + ACCIONES --}}
    <div class="row g-4 mb-4">

        {{-- Desglose por rol --}}
        <div class="col-md-4">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-pie text-primary"></i>
                    <span class="fw-bold">Usuarios por rol</span>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success">Alumnos</span>
                        </div>
                        <span class="fw-bold fs-5">{{ $stats['total_students'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">Profesores</span>
                        </div>
                        <span class="fw-bold fs-5">{{ $stats['total_teachers'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark">Pendientes</span>
                        </div>
                        <span class="fw-bold fs-5">{{ $stats['pending_bookings'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="col-md-8">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-bolt text-primary"></i>
                    <span class="fw-bold">Acciones rápidas</span>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <a href="{{ route('admin.users') }}"
                               class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="fas fa-users fs-4"></i>
                                <span class="small fw-semibold">Gestionar usuarios</span>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{ route('admin.classes') }}"
                               class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="fas fa-chalkboard-teacher fs-4"></i>
                                <span class="small fw-semibold">Gestionar clases</span>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{ route('admin.reviews') }}"
                               class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="fas fa-star fs-4"></i>
                                <span class="small fw-semibold">Gestionar reseñas</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLAS RECIENTES --}}
    <div class="row g-4">

        {{-- Usuarios recientes --}}
        <div class="col-lg-6">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user-plus text-primary"></i>
                        <span class="fw-bold">Usuarios recientes</span>
                    </div>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="p-4">
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small fw-semibold">Nombre</th>
                                        <th class="small fw-semibold">Rol</th>
                                        <th class="small fw-semibold">Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td class="small fw-semibold">{{ $user->name }}</td>
                                            <td>
                                                <span class="badge
                                                    @switch($user->role)
                                                        @case('admin')   bg-danger  @break
                                                        @case('teacher') bg-primary @break
                                                        @default         bg-success @break
                                                    @endswitch">
                                                    @switch($user->role)
                                                        @case('admin')   Admin    @break
                                                        @case('teacher') Profesor @break
                                                        @default         Alumno   @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->is_blocked ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $user->is_blocked ? 'Bloqueado' : 'Activo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users') }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small mb-0">No hay usuarios recientes.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Clases recientes --}}
        <div class="col-lg-6">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-primary"></i>
                        <span class="fw-bold">Clases recientes</span>
                    </div>
                    <a href="{{ route('admin.classes') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="p-4">
                    @if($recentClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small fw-semibold">Título</th>
                                        <th class="small fw-semibold">Profesor</th>
                                        <th class="small fw-semibold">Precio</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClasses as $class)
                                        <tr>
                                            <td class="small fw-semibold">{{ Str::limit($class->title, 25) }}</td>
                                            <td class="small text-muted">{{ $class->teacher->name }}</td>
                                            <td class="small text-primary fw-bold">
                                                €{{ number_format($class->price_per_hour, 2) }}/h
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.classes') }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small mb-0">No hay clases recientes.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection