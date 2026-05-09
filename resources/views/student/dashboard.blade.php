@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Hola, {{ auth()->user()->name }} 👋</h3>
            <p class="text-muted mb-0">Bienvenido a tu panel de alumno</p>
        </div>
        <a href="{{ route('student.search') }}" class="btn btn-primary">
            <i class="fas fa-search me-2"></i>Buscar clases
        </a>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="fw-bold fs-4">{{ $totalBookings ?? 0 }}</div>
                <div class="text-muted small">Reservas totales</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="fw-bold fs-4">{{ $completedClasses ?? 0 }}</div>
                <div class="text-muted small">Clases completadas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                    <i class="fas fa-star"></i>
                </div>
                <div class="fw-bold fs-4">{{ $pendingReviews->count() ?? 0 }}</div>
                <div class="text-muted small">Valoraciones pendientes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="fw-bold fs-4">{{ $favoritesCount ?? 0 }}</div>
                <div class="text-muted small">Favoritos</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- PRÓXIMA CLASE --}}
        <div class="col-lg-6">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-calendar-alt text-primary"></i>
                    <span class="fw-bold">Próxima clase</span>
                </div>
                <div class="p-4">
                    @if($nextClass)
                        <div class="d-flex align-items-center mb-3">
                            @if($nextClass->class->teacher->profile_photo)
                                <img src="{{ asset('storage/' . $nextClass->class->teacher->profile_photo) }}"
                                     class="rounded-circle me-3" width="52" height="52" alt="Profesor">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                     style="width:52px;height:52px;font-size:1.2rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $nextClass->class->title }}</div>
                                <div class="text-muted small">{{ $nextClass->class->teacher->name }}</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2 text-muted small">
                                <i class="fas fa-clock text-primary me-2"></i>
                                {{ $nextClass->scheduled_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ ucfirst($nextClass->class->modality) }}
                            </div>
                        </div>
                        @if($nextClass->meeting_url)
                            <a href="{{ $nextClass->meeting_url }}" target="_blank" class="btn btn-primary w-100">
                                <i class="fas fa-video me-2"></i>Unirse a la videollamada
                            </a>
                        @else
                            <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-2"></i>Ver detalles
                            </a>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-3">No tienes clases programadas</p>
                            <a href="{{ route('student.search') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i>Buscar clases
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- VALORACIONES PENDIENTES --}}
        <div class="col-lg-6">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-star text-warning"></i>
                    <span class="fw-bold">Valoraciones pendientes</span>
                    @if($pendingReviews->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingReviews->count() }}</span>
                    @endif
                </div>
                <div class="p-4">
                    @if($pendingReviews->count() > 0)
                        @foreach($pendingReviews as $review)
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center">
                                    @if($review->class->teacher->profile_photo)
                                        <img src="{{ asset('storage/' . $review->class->teacher->profile_photo) }}"
                                             class="rounded-circle me-3" width="40" height="40" alt="Profesor">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                             style="width:40px;height:40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold small">{{ $review->class->teacher->name }}</div>
                                        <div class="text-muted" style="font-size:.8rem">{{ $review->class->title }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('student.review.create', $review) }}" class="btn btn-sm btn-warning fw-semibold">
                                    <i class="fas fa-star me-1"></i>Valorar
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">¡Todas tus clases están valoradas!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- NIVELES DE EVALUACIÓN --}}
        <div class="col-lg-4">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-line text-primary"></i>
                    <span class="fw-bold">Niveles de Evaluación</span>
                </div>
                <div class="p-4">
                    @if($assessments->count() > 0)
                        @foreach($assessments as $assessment)
                            <div class="mb-3 p-3 border rounded assessment-level-{{ $assessment['level'] }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-capitalize">{{ ucfirst($assessment['subject']) }}</span>
                                    <span class="badge level-badge-{{ $assessment['level'] }}">
                                        {{ ucfirst($assessment['level']) }}
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    Evaluado: {{ $assessment['evaluated_at']->format('d/m/Y') }}
                                    @if($assessment['evaluations_count'] > 1)
                                        <span class="ms-2">({{ $assessment['evaluations_count'] }} evaluaciones)</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">No has realizado evaluaciones aún</p>
                            <a href="{{ route('assessment.create') }}" class="btn btn-primary btn-sm mt-3">
                                <i class="fas fa-clipboard-list me-2"></i>Hacer evaluación
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- NOTIFICACIONES --}}
        <div class="col-lg-8">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-bell text-primary"></i>
                    <span class="fw-bold">Notificaciones</span>
                </div>
                <div class="p-4">
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="notif-icon me-3">
                                    @if($notification['type'] === 'aceptada')
                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                    @elseif($notification['type'] === 'rechazada')
                                        <i class="fas fa-times-circle text-danger fs-4"></i>
                                    @elseif($notification['type'] === 'completada')
                                        <i class="fas fa-graduation-cap text-primary fs-4"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="mb-1 small">{{ $notification['message'] }}</p>
                                    <span class="text-muted" style="font-size:.78rem">
                                        {{ $notification['date']->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">No tienes notificaciones nuevas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ACCIONES RÁPIDAS --}}
        <div class="col-lg-4">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-bolt text-primary"></i>
                    <span class="fw-bold">Acciones rápidas</span>
                </div>
                <div class="p-4 d-grid gap-2">
                    <a href="{{ route('student.search') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Buscar clases
                    </a>
                    <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar me-2"></i>Mis reservas
                    </a>
                    <a href="{{ route('student.favorites') }}" class="btn btn-outline-primary">
                        <i class="fas fa-heart me-2"></i>Favoritos
                    </a>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i>Mi perfil
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection