@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-shield-alt text-primary me-2"></i>
                Panel de Administración
            </h1>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Usuarios</h5>
                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Clases</h5>
                    <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Reservas</h5>
                    <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Reservas Pendientes</h5>
                    <h3 class="mb-0">{{ $stats['pending_bookings'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Usuarios por Rol</h6>
                    <div class="row">
                        <div class="col-6">
                            <strong>Alumnos:</strong> {{ $stats['total_students'] }}
                        </div>
                        <div class="col-6">
                            <strong>Profesores:</strong> {{ $stats['total_teachers'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Reseñas</h6>
                    <h4 class="text-center">{{ $stats['total_reviews'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>
                                Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.classes') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Gestionar Clases
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reviews') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-star me-2"></i>
                                Gestionar Reseñas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Usuarios Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Usuarios Recientes
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'primary' : 'success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->is_blocked)
                                                    <span class="badge bg-danger">Bloqueado</span>
                                                @else
                                                    <span class="badge bg-success">Activo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay usuarios recientes.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Clases Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Clases Recientes
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Profesor</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClasses as $class)
                                        <tr>
                                            <td>{{ $class->title }}</td>
                                            <td>{{ $class->teacher->name }}</td>
                                            <td>{{ $class->price_per_hour }}€/h</td>
                                            <td>
                                                <a href="{{ route('admin.classes') }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay clases recientes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
