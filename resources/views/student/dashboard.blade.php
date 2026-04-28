@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h3 class="mb-1">Dashboard del Alumno</h3>
        <p class="text-muted mb-0">Bienvenido de nuevo, {{ auth()->user()->name }}</p>
    </div>

    <div class="row">
        <!-- PRÓXIMA CLASE -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Próxima Clase
                    </h5>
                </div>
                <div class="card-body">
                    @if($nextClass)
                        <div class="d-flex align-items-center mb-3">
                            @if($nextClass->class->teacher->profile_photo)
                                <img src="{{ asset('storage/' . $nextClass->class->teacher->profile_photo) }}" 
                                     class="rounded-circle me-3" width="50" height="50" alt="Profesor">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $nextClass->class->title }}</h6>
                                <p class="text-muted mb-0">{{ $nextClass->class->teacher->name }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                <strong>{{ $nextClass->scheduled_at->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span class="badge bg-success">Aceptada</span>
                            </div>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('student.bookings') }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>Ver Detalles
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No tienes clases programadas</h6>
                            <p class="text-muted small">Busca nuevas clases y reserva tu primera clase</p>
                            <a href="{{ route('student.search') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Buscar Clases
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- VALORACIONES PENDIENTES -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>Valoraciones Pendientes
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingReviews->count() > 0)
                        @foreach($pendingReviews as $review)
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 
                                        @if(!$loop->last) border-bottom @endif">
                                <div class="d-flex align-items-center">
                                    @if($review->class->teacher->profile_photo)
                                        <img src="{{ asset('storage/' . $review->class->teacher->profile_photo) }}" 
                                             class="rounded-circle me-3" width="40" height="40" alt="Profesor">
                                    @else
                                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $review->class->teacher->name }}</h6>
                                        <small class="text-muted">{{ $review->class->title }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('student.review.create', $review) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-star me-1"></i>Valorar
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No tienes valoraciones pendientes</h6>
                            <p class="text-muted small">¡Todas tus clases están valoradas!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- NOTIFICACIONES -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Notificaciones
                    </h5>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="d-flex align-items-start mb-3 pb-3 
                                        @if(!$loop->last) border-bottom @endif">
                                <div class="me-3">
                                    @if($notification['type'] === 'aceptada')
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    @elseif($notification['type'] === 'rechazada')
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    @elseif($notification['type'] === 'completada')
                                        <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1">{{ $notification['message'] }}</p>
                                    <small class="text-muted">
                                        {{ $notification['date']->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No tienes notificaciones</h6>
                            <p class="text-muted small">Te notificaremos cuando haya cambios en tus reservas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.search') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Buscar Clases
                        </a>
                        <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar me-2"></i>Mis Reservas
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
