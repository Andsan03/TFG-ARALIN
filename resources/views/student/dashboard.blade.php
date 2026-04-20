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
                    <span class="badge bg-success">Alumno</span>
                    <p class="text-muted small mt-2">{{ auth()->user()->bio ?? 'Sin biografía' }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('student.search') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-search me-2"></i> Buscar Clases
                    </a>
                    <a href="{{ route('student.bookings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar me-2"></i> Mis Reservas
                    </a>
                    <a href="{{ route('student.favorites') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-heart me-2"></i> Profesores Favoritos
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
                                    <h4 class="card-title">{{ $totalBookings }}</h4>
                                    <p class="card-text">Total Reservas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar fa-2x opacity-75"></i>
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
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $acceptedBookings }}</h4>
                                    <p class="card-text">Aceptadas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $completedBookings }}</h4>
                                    <p class="card-text">Completadas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>Reservas Recientes
                            </h5>
                            <a href="{{ route('student.bookings') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                        </div>
                        <div class="card-body">
                            @if($recentBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Clase</th>
                                                <th>Profesor</th>
                                                <th>Estado</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->class->title }}</td>
                                                <td>{{ $booking->class->teacher->name }}</td>
                                                <td>
                                                    @switch($booking->status)
                                                        @case('pendiente')
                                                            <span class="badge bg-warning">Pendiente</span>
                                                        @break
                                                        @case('aceptada')
                                                            <span class="badge bg-success">Aceptada</span>
                                                        @break
                                                        @case('completada')
                                                            <span class="badge bg-info">Completada</span>
                                                        @break
                                                        @case('rechazada')
                                                            <span class="badge bg-danger">Rechazada</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $booking->scheduled_at ? $booking->scheduled_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No tienes reservas aún.</p>
                                    <a href="{{ route('student.search') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Buscar Clases
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Favorite Teachers -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-heart me-2"></i>Profesores Favoritos
                            </h5>
                            <a href="{{ route('student.favorites') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                        </div>
                        <div class="card-body">
                            @if($favoriteTeachers->count() > 0)
                                @foreach($favoriteTeachers as $favorite)
                                <div class="d-flex align-items-center mb-3">
                                    @if($favorite->teacher->profile_photo)
                                        <img src="{{ asset('storage/' . $favorite->teacher->profile_photo) }}" 
                                             class="rounded-circle me-3" width="40" height="40" alt="Teacher">
                                    @else
                                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $favorite->teacher->name }}</h6>
                                        <small class="text-muted">Profesor</small>
                                    </div>
                                    <a href="{{ route('student.class.show', $favorite->teacher->classes->first()->id ?? '#') }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Ver Clases
                                    </a>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No tienes profesores favoritos aún.</p>
                                    <a href="{{ route('student.search') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Buscar Profesores
                                    </a>
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
