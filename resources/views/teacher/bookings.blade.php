@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de reservas</h3>
            <p class="text-muted mb-0">Acepta, rechaza y gestiona las reservas de tus alumnos</p>
        </div>
        {{-- Filtros por estado --}}
        <div class="btn-group d-none d-md-flex" role="group">
            <button type="button" class="btn btn-primary btn-sm active" onclick="filtrar('all', this)">
                Todas
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrar('pendiente', this)">
                Pendientes
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrar('aceptada', this)">
                Aceptadas
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrar('completada', this)">
                Completadas
            </button>
        </div>
    </div>

    @if($bookings->count() > 0)

        <p class="text-muted small mb-3">{{ $bookings->total() }} reservas en total</p>

        <div class="row g-4" id="bookings-list">
            @foreach($bookings as $booking)
                <div class="col-md-6 col-lg-4 booking-item" data-status="{{ $booking->status }}">
                    <div class="booking-card bg-white border rounded-3 h-100">

                        {{-- Barra de estado --}}
                        <div class="booking-status-bar
                            @switch($booking->status)
                                @case('pendiente')  status-pendiente  @break
                                @case('aceptada')   status-aceptada   @break
                                @case('completada') status-completada @break
                                @case('rechazada')  status-rechazada  @break
                            @endswitch
                        "></div>

                        <div class="p-4">

                            {{-- Estado y fecha --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge
                                    @switch($booking->status)
                                        @case('pendiente')  bg-warning text-dark @break
                                        @case('aceptada')   bg-success           @break
                                        @case('completada') bg-primary           @break
                                        @case('rechazada')  bg-danger            @break
                                    @endswitch">
                                    @switch($booking->status)
                                        @case('pendiente')  <i class="fas fa-clock me-1"></i>Pendiente  @break
                                        @case('aceptada')   <i class="fas fa-check me-1"></i>Aceptada   @break
                                        @case('completada') <i class="fas fa-graduation-cap me-1"></i>Completada @break
                                        @case('rechazada')  <i class="fas fa-times me-1"></i>Rechazada  @break
                                    @endswitch
                                </span>
                                <small class="text-muted">{{ $booking->created_at->format('d/m/Y') }}</small>
                            </div>

                            {{-- Alumno --}}
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->student->profile_photo)
                                    <img src="{{ asset('storage/' . $booking->student->profile_photo) }}"
                                         class="rounded-circle me-2" width="38" height="38" alt="Alumno">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2"
                                         style="width:38px;height:38px;font-weight:700">
                                        {{ substr($booking->student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold small">{{ $booking->student->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">{{ $booking->student->email }}</div>
                                </div>
                            </div>

                            {{-- Clase --}}
                            <div class="fw-bold small mb-1">{{ Str::limit($booking->class->title, 40) }}</div>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary small">
                                    {{ $booking->class->category }}
                                </span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary small">
                                    <i class="fas fa-euro-sign me-1"></i>{{ number_format($booking->class->price_per_hour, 2) }}/h
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info small">
                                    {{ ucfirst($booking->class->modality) }}
                                </span>
                            </div>

                            {{-- Fecha programada --}}
                            @if($booking->scheduled_at)
                                <div class="d-flex align-items-center text-muted small mb-3">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                                </div>
                            @endif

                            {{-- Nivel del alumno (assessment) --}}
                            @if($booking->assessment)
                                <div class="bg-light rounded-3 p-2 mb-3">
                                    <div class="small fw-semibold mb-1">
                                        <i class="fas fa-brain text-primary me-1"></i>Nivel del alumno
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        @switch($booking->assessment->detected_level)
                                            @case('beginner')     Principiante @break
                                            @case('intermediate') Intermedio   @break
                                            @case('advanced')     Avanzado     @break
                                        @endswitch
                                    </span>
                                    <p class="text-muted mb-0 mt-1" style="font-size:.75rem">
                                        {{ Str::limit($booking->assessment->ai_recommendation, 80) }}
                                    </p>
                                </div>
                            @endif

                            {{-- Fecha y categoría --}}
                            <div class="d-flex gap-3 mb-3 small text-muted">
                                <div>
                                    <i class="fas fa-clock text-primary me-1"></i>
                                    {{ $booking->scheduled_at ? $booking->scheduled_at->format('d/m/Y H:i') : 'Por definir' }}
                                </div>
                                <div>
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    {{ $booking->class->category }}
                                </div>
                                <div>
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    {{ ucfirst($booking->class->modality) }}
                                </div>
                            </div>

                            {{-- Enlace videollamada si está aceptada y es online --}}
                            @if($booking->status === 'aceptada' && $booking->meeting_url)
                                <a href="{{ $booking->meeting_url }}" target="_blank"
                                   class="btn btn-success btn-sm w-100 mb-3">
                                    <i class="fas fa-video me-2"></i>Unirse a la videollamada
                                </a>
                            @endif

                        </div>

                        {{-- ACCIONES --}}
                        <div class="border-top p-3">
                            @if($booking->status === 'pendiente')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('teacher.bookings.accept', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold">
                                            <i class="fas fa-check me-1"></i>Aceptar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}" class="flex-fill"
                                          onsubmit="return confirm('¿Seguro que quieres rechazar esta reserva?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-times me-1"></i>Rechazar
                                        </button>
                                    </form>
                                </div>

                            @elseif($booking->status === 'aceptada')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('teacher.bookings.complete', $booking) }}" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                                            <i class="fas fa-graduation-cap me-1"></i>Completar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.bookings.reject', $booking) }}" class="flex-fill"
                                          onsubmit="return confirm('¿Seguro que quieres cancelar esta reserva?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-times me-1"></i>Cancelar
                                        </button>
                                    </form>
                                </div>

                            @else
                                <p class="text-muted small text-center mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $booking->status === 'completada' ? 'Clase completada' : 'Reserva rechazada' }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Aún no tienes reservas</h5>
            <p class="text-muted small mb-4">
                Cuando los alumnos reserven tus clases aparecerán aquí.
            </p>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
            </a>
        </div>
    @endif

</div>

<script>
function filtrar(status, btn) {
    // Actualizar botones
    document.querySelectorAll('.btn-group button').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline-primary');
    });
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-primary', 'active');

    // Mostrar/ocultar tarjetas
    document.querySelectorAll('.booking-item').forEach(item => {
        item.style.display = (status === 'all' || item.dataset.status === status) ? '' : 'none';
    });
}
</script>

@endsection