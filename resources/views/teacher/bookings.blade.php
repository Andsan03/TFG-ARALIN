@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="fas fa-calendar-check me-2"></i>Gestión de Reservas
        </h3>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="filterBookings('all')">
                Todas
            </button>
            <button type="button" class="btn btn-outline-warning" onclick="filterBookings('pending')">
                Pendientes
            </button>
            <button type="button" class="btn btn-outline-success" onclick="filterBookings('accepted')">
                Aceptadas
            </button>
            <button type="button" class="btn btn-outline-info" onclick="filterBookings('completed')">
                Completadas
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($bookings->count() > 0)
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-md-6 col-lg-4 mb-4" data-status="{{ $booking->status }}">
                    <div class="card h-100 shadow-sm">
                        <!-- Header con estado -->
                        <div class="card-header bg-{{ $booking->status === 'pendiente' ? 'warning' : ($booking->status === 'aceptada' ? 'success' : ($booking->status === 'completada' ? 'info' : 'danger')) }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="fw-bold">
                                    <i class="fas fa-{{ $booking->status === 'pendiente' ? 'clock' : ($booking->status === 'aceptada' ? 'check-circle' : ($booking->status === 'completada' ? 'check-double' : 'times-circle')) }} me-1"></i>
                                    {{ ucfirst($booking->status) }}
                                </small>
                                <small>{{ $booking->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Información del alumno -->
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->student->profile_photo)
                                    <img src="{{ asset('storage/' . $booking->student->profile_photo) }}" 
                                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        {{ substr($booking->student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $booking->student->name }}</h6>
                                    <small class="text-muted">{{ $booking->student->email }}</small>
                                </div>
                            </div>

                            <!-- Información de la clase -->
                            <h6 class="card-title">{{ $booking->class->title }}</h6>
                            <p class="card-text text-muted small">
                                {{ Str::limit($booking->class->description, 80) }}
                            </p>

                            <!-- Detalles -->
                            <div class="mb-3">
                                <div class="row small">
                                    <div class="col-6">
                                        <i class="fas fa-tag me-1"></i>{{ $booking->class->category }}
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-signal me-1"></i>{{ $booking->class->level }}
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-euro-sign me-1"></i>{{ number_format($booking->class->price_per_hour, 2) }}/h
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-laptop me-1"></i>{{ $booking->class->modality === 'online' ? 'Online' : ($booking->class->modality === 'presential' ? 'Presencial' : 'Mixta') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha programada -->
                            @if($booking->scheduled_at)
                                <div class="alert alert-info py-2 mb-3">
                                    <small class="fw-bold">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            @endif

                            <!-- URL de reunión (si aplica) -->
                            @if($booking->meeting_url && $booking->status === 'aceptada')
                                <div class="mb-3">
                                    <a href="{{ $booking->meeting_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-video me-1"></i>Unirse a la reunión
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Acciones -->
                        <div class="card-footer bg-light">
                            @if($booking->status === 'pendiente')
                                <div class="btn-group w-100" role="group">
                                    <form method="POST" action="{{ route('teacher.bookings.accept', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-check me-1"></i>Aceptar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm w-100" 
                                                onclick="return confirm('¿Estás seguro de rechazar esta reserva?')">
                                            <i class="fas fa-times me-1"></i>Rechazar
                                        </button>
                                    </form>
                                </div>
                            @elseif($booking->status === 'aceptada')
                                <div class="btn-group w-100" role="group">
                                    <form method="POST" action="{{ route('teacher.bookings.complete', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm w-100">
                                            <i class="fas fa-check-double me-1"></i>Completar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100" 
                                                onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                            <i class="fas fa-times me-1"></i>Cancelar
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $booking->status === 'completada' ? 'Reserva completada' : 'Reserva cancelada' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <!-- Mensaje cuando no hay reservas -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-calendar-times fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">Aún no tienes reservas</h4>
            <p class="text-muted mb-4">
                Cuando los alumnos reserven tus clases, aparecerán aquí para que puedas gestionarlas.
            </p>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
    @endif
</div>

<script>
function filterBookings(status) {
    const cards = document.querySelectorAll('[data-status]');
    const buttons = document.querySelectorAll('.btn-group button');
    
    // Actualizar botones
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.toLowerCase().includes(status) || 
            (status === 'all' && btn.textContent === 'Todas')) {
            btn.classList.add('active');
        }
    });
    
    // Filtrar cards
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.parentElement.style.display = 'block';
        } else {
            card.parentElement.style.display = 'none';
        }
    });
}
</script>
@endsection
