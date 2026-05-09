@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Mis reservas</h3>
            <p class="text-muted mb-0">Gestiona todas tus reservas de clases</p>
        </div>
        <a href="{{ route('student.search') }}" class="btn btn-primary d-none d-md-inline-block">
            <i class="fas fa-search me-2"></i>Buscar clases
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="search-filters bg-white border rounded-3 p-4 mb-4">
        <form method="GET" action="{{ route('student.bookings') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}" placeholder="Título o profesor...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Estado</label>
                    <select class="form-select" name="status">
                        <option value="">Todos los estados</option>
                        <option value="pendiente"   {{ request('status') == 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                        <option value="aceptada"    {{ request('status') == 'aceptada'    ? 'selected' : '' }}>Aceptada</option>
                        <option value="completada"  {{ request('status') == 'completada'  ? 'selected' : '' }}>Completada</option>
                        <option value="rechazada"   {{ request('status') == 'rechazada'   ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Categoría</label>
                    <select class="form-select" name="category">
                        <option value="">Todas las categorías</option>
                        <option value="matematicas"  {{ request('category') == 'matematicas'  ? 'selected' : '' }}>Matemáticas</option>
                        <option value="ciencias"     {{ request('category') == 'ciencias'     ? 'selected' : '' }}>Ciencias</option>
                        <option value="idiomas"      {{ request('category') == 'idiomas'      ? 'selected' : '' }}>Idiomas</option>
                        <option value="arte"         {{ request('category') == 'arte'         ? 'selected' : '' }}>Arte</option>
                        <option value="musica"       {{ request('category') == 'musica'       ? 'selected' : '' }}>Música</option>
                        <option value="deporte"      {{ request('category') == 'deporte'      ? 'selected' : '' }}>Deporte</option>
                        <option value="programacion" {{ request('category') == 'programacion' ? 'selected' : '' }}>Programación</option>
                        <option value="otros"        {{ request('category') == 'otros'        ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
                <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- LISTA DE RESERVAS --}}
    @if($bookings->count() > 0)

        <p class="text-muted small mb-3">{{ $bookings->total() }} reservas encontradas</p>

        <div class="row g-4">
            @foreach($bookings as $booking)
                <div class="col-lg-6">
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

                            {{-- Título y estado --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="fw-bold mb-0 me-2">{{ $booking->class->title }}</h6>
                                <span class="badge flex-shrink-0
                                    @switch($booking->status)
                                        @case('pendiente')  bg-warning text-dark @break
                                        @case('aceptada')   bg-success           @break
                                        @case('completada') bg-primary           @break
                                        @case('rechazada')  bg-danger            @break
                                    @endswitch
                                ">
                                    @switch($booking->status)
                                        @case('pendiente')  <i class="fas fa-clock me-1"></i>Pendiente  @break
                                        @case('aceptada')   <i class="fas fa-check me-1"></i>Aceptada   @break
                                        @case('completada') <i class="fas fa-graduation-cap me-1"></i>Completada @break
                                        @case('rechazada')  <i class="fas fa-times me-1"></i>Rechazada  @break
                                    @endswitch
                                </span>
                            </div>

                            {{-- Profesor --}}
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $booking->class->teacher->profile_photo) }}"
                                         class="rounded-circle me-2" width="36" height="36" alt="Profesor">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2"
                                         style="width:36px;height:36px;font-size:.9rem">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold small">{{ $booking->class->teacher->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">Profesor</div>
                                </div>
                                <div class="ms-auto text-end">
                                    <div class="fw-bold text-primary">€{{ number_format($booking->class->price_per_hour, 2) }}</div>
                                    <div class="text-muted" style="font-size:.75rem">/hora</div>
                                </div>
                            </div>

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
                            </div>

                            {{-- Enlace videollamada si está aceptada y es online --}}
                            @if($booking->status === 'aceptada' && $booking->meeting_url)
                                <a href="{{ $booking->meeting_url }}" target="_blank"
                                   class="btn btn-success btn-sm w-100 mb-3">
                                    <i class="fas fa-video me-2"></i>Unirse a la videollamada
                                </a>
                            @endif

                            {{-- Acciones --}}
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('student.class.show', $booking->class) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Ver clase
                                </a>

                                @if(in_array($booking->status, ['pendiente', 'aceptada']))
                                    <form method="POST"
                                          action="{{ route('student.bookings.cancel', $booking) }}"
                                          onsubmit="return confirm('¿Seguro que quieres cancelar esta reserva?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times me-1"></i>Cancelar
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === 'completada' && !$booking->review)
                                    <a href="{{ route('student.review.create', $booking) }}"
                                       class="btn btn-sm btn-warning fw-semibold">
                                        <i class="fas fa-star me-1"></i>Valorar
                                    </a>
                                @endif

                                @if($booking->review)
                                    <span class="badge bg-success d-flex align-items-center">
                                        <i class="fas fa-star me-1"></i>
                                        Valorada {{ $booking->review->rating }}/5
                                    </span>
                                @endif
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="border-top px-4 py-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Reservada el {{ $booking->created_at->format('d/m/Y H:i') }}
                            </small>
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
            <h5 class="text-muted">No tienes reservas todavía</h5>
            <p class="text-muted small mb-4">Comienza buscando clases y reserva tu primera clase.</p>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Buscar clases
            </a>
        </div>

    @endif

</div>

@endsection