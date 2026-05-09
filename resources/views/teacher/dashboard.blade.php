@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Hola, {{ auth()->user()->name }} 👋</h3>
            <p class="text-muted mb-0">Bienvenido a tu panel de profesor</p>
        </div>
        <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva clase
        </a>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                    <i class="fas fa-book"></i>
                </div>
                <div class="fw-bold fs-4">{{ $totalClasses }}</div>
                <div class="text-muted small">Total clases</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="fw-bold fs-4">{{ $activeClasses }}</div>
                <div class="text-muted small">Clases activas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="fw-bold fs-4">{{ $totalBookings }}</div>
                <div class="text-muted small">Total reservas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="fw-bold fs-4">{{ $pendingBookings }}</div>
                <div class="text-muted small">Pendientes</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- CLASES RECIENTES --}}
        <div class="col-lg-8">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-book text-primary"></i>
                        <span class="fw-bold">Clases recientes</span>
                    </div>
                    <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-primary">
                        Ver todas
                    </a>
                </div>
                <div class="p-4">
                    @if($recentClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small fw-semibold">Título</th>
                                        <th class="small fw-semibold">Categoría</th>
                                        <th class="small fw-semibold">Precio</th>
                                        <th class="small fw-semibold">Estado</th>
                                        <th class="small fw-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClasses as $class)
                                        <tr>
                                            <td class="small fw-semibold">{{ Str::limit($class->title, 30) }}</td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $class->category }}
                                                </span>
                                            </td>
                                            <td class="small text-primary fw-bold">
                                                €{{ number_format($class->price_per_hour, 2) }}/h
                                            </td>
                                            <td>
                                                @if($class->is_active)
                                                    <span class="badge bg-success">Activa</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactiva</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('teacher.classes.edit', $class) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('teacher.classes.toggle', $class) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                                title="{{ $class->is_active ? 'Desactivar' : 'Activar' }}">
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
                            <i class="fas fa-book-open fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-3">No tienes clases creadas aún</p>
                            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Crear primera clase
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RESERVAS RECIENTES --}}
        <div class="col-lg-4">
            <div class="dashboard-card bg-white border rounded-3 h-100">
                <div class="dashboard-card-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar text-primary"></i>
                        <span class="fw-bold">Reservas recientes</span>
                    </div>
                    <a href="{{ route('teacher.bookings') }}" class="btn btn-sm btn-outline-primary">
                        Ver todas
                    </a>
                </div>
                <div class="p-4">
                    @if($recentBookings->count() > 0)
                        @foreach($recentBookings as $booking)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                                {{-- Barra de estado --}}
                                <div class="booking-status-bar rounded mb-2
                                    @switch($booking->status)
                                        @case('pendiente')  status-pendiente  @break
                                        @case('aceptada')   status-aceptada   @break
                                        @case('completada') status-completada @break
                                        @case('rechazada')  status-rechazada  @break
                                    @endswitch
                                " style="height:3px"></div>

                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="fw-semibold small">{{ Str::limit($booking->class->title, 25) }}</div>
                                    <span class="badge ms-1
                                        @switch($booking->status)
                                            @case('pendiente')  bg-warning text-dark @break
                                            @case('aceptada')   bg-success           @break
                                            @case('completada') bg-primary           @break
                                            @case('rechazada')  bg-danger            @break
                                        @endswitch"
                                        style="font-size:.7rem">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>

                                <div class="text-muted mb-1" style="font-size:.8rem">
                                    <i class="fas fa-user me-1"></i>{{ $booking->student->name }}
                                </div>

                                @if($booking->scheduled_at)
                                    <div class="text-muted mb-2" style="font-size:.78rem">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif

                                @if($booking->status === 'pendiente')
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('teacher.bookings.accept', $booking) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                                <i class="fas fa-check me-1"></i>Aceptar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>Rechazar
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted small mb-0">
                                Las reservas aparecerán aquí cuando los alumnos te contacten.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection