@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                 class="rounded-circle" width="80" height="80" alt="Profile">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    <span class="badge bg-primary">Profesor</span>
                    <p class="text-muted small mt-2">{{ auth()->user()->bio ?? 'Sin biografía' }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('teacher.dashboard') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('teacher.classes') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-book me-2"></i> Mis Clases
                    </a>
                    <a href="{{ route('teacher.classes.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i> Nueva Clase
                    </a>
                    <a href="{{ route('teacher.bookings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar me-2"></i> Reservas
                    </a>
                    <a href="{{ route('teacher.reviews') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-star me-2"></i> Reseñas
                    </a>
                    <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Mi Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $totalClasses }}</h4>
                                    <p class="card-text">Total Clases</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-book fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $activeClasses }}</h4>
                                    <p class="card-text">Activas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $totalBookings }}</h4>
                                    <p class="card-text">Total Reservas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $pendingBookings }}</h4>
                                    <p class="card-text">Pendientes</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="row">
                <!-- Recent Classes -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-book me-2"></i>Clases Recientes
                            </h5>
                            <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                        </div>
                        <div class="card-body">
                            @if($recentClasses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Categoría</th>
                                                <th>Precio</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentClasses as $class)
                                            <tr>
                                                <td>{{ $class->title }}</td>
                                                <td><span class="badge bg-info">{{ $class->category }}</span></td>
                                                <td>€{{ $class->price_per_hour }}/h</td>
                                                <td>
                                                    @if($class->is_active)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('teacher.classes.edit', $class) }}" 
                                                           class="btn btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('teacher.classes.toggle', $class) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-warning">
                                                                <i class="fas fa-power-off"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No tienes clases creadas aún.</p>
                                    <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Crear Primera Clase
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar me-2"></i>Reservas Recientes
                            </h5>
                            <a href="{{ route('teacher.bookings') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                        </div>
                        <div class="card-body">
                            @if($recentBookings->count() > 0)
                                @foreach($recentBookings as $booking)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $booking->class->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $booking->student->name }}
                                            </small>
                                        </div>
                                        <span class="badge 
                                            @switch($booking->status)
                                                @case('pendiente') bg-warning @break
                                                @case('aceptada') bg-success @break
                                                @case('completada') bg-info @break
                                                @case('rechazada') bg-danger @break
                                            @endswitch
                                        ">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    @if($booking->scheduled_at)
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                    @if($booking->status === 'pendiente')
                                        <div class="mt-2">
                                            <form method="POST" action="{{ route('teacher.bookings.accept', $booking) }}" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Aceptar
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times me-1"></i>Rechazar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No tienes reservas aún.</p>
                                    <p class="text-muted small">Las reservas aparecerán aquí cuando los alumnos te contacten.</p>
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
