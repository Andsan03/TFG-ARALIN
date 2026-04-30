@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Mis Reservas</h3>
            <p class="text-muted mb-0">Gestiona todas tus reservas de clases</p>
        </div>
        <div>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Buscar Clases
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('student.bookings') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="aceptada" {{ request('status') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                            <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="rechazada" {{ request('status') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Todas las categorías</option>
                            <option value="matematicas" {{ request('category') == 'matematicas' ? 'selected' : '' }}>Matemáticas</option>
                            <option value="ciencias" {{ request('category') == 'ciencias' ? 'selected' : '' }}>Ciencias</option>
                            <option value="idiomas" {{ request('category') == 'idiomas' ? 'selected' : '' }}>Idiomas</option>
                            <option value="arte" {{ request('category') == 'arte' ? 'selected' : '' }}>Arte</option>
                            <option value="musica" {{ request('category') == 'musica' ? 'selected' : '' }}>Música</option>
                            <option value="deporte" {{ request('category') == 'deporte' ? 'selected' : '' }}>Deporte</option>
                            <option value="programacion" {{ request('category') == 'programacion' ? 'selected' : '' }}>Programación</option>
                            <option value="negocios" {{ request('category') == 'negocios' ? 'selected' : '' }}>Negocios</option>
                            <option value="otros" {{ request('category') == 'otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Buscar por título o profesor">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                        <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Reservas -->
    @if($bookings->count() > 0)
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Header con estado -->
                        <div class="card-header d-flex justify-content-between align-items-center
                            @switch($booking->status)
                                @case('pendiente')
                                    bg-warning text-dark
                                @break
                                @case('aceptada')
                                    bg-success text-white
                                @break
                                @case('completada')
                                    bg-info text-white
                                @break
                                @case('rechazada')
                                    bg-danger text-white
                                @break
                            @endswitch
                        ">
                            <div>
                                <h6 class="mb-0">{{ $booking->class->title }}</h6>
                                <small class="mb-0">
                                    @switch($booking->status)
                                        @case('pendiente')
                                            <i class="fas fa-clock me-1"></i>Esperando confirmación
                                        @break
                                        @case('aceptada')
                                            <i class="fas fa-check-circle me-1"></i>Clase confirmada
                                        @break
                                        @case('completada')
                                            <i class="fas fa-graduation-cap me-1"></i>Clase completada
                                        @break
                                        @case('rechazada')
                                            <i class="fas fa-times-circle me-1"></i>Reserva rechazada
                                        @break
                                    @endswitch
                                </small>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">
                                    €{{ number_format($booking->class->price_per_hour, 2) }}/h
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Información del profesor -->
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $booking->class->teacher->profile_photo) }}" 
                                         class="rounded-circle me-3" width="40" height="40" alt="Profesor">
                                @else
                                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $booking->class->teacher->name }}</h6>
                                    <small class="text-muted">Profesor</small>
                                </div>
                            </div>

                            <!-- Detalles de la reserva -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Fecha y hora</small>
                                        <strong>{{ $booking->scheduled_at ? $booking->scheduled_at->format('d/m/Y H:i') : 'Por definir' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Categoría</small>
                                        <span class="badge bg-primary">{{ $booking->class->category }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($booking->class->description, 100) }}
                            </p>

                            <!-- Acciones -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('student.class.show', $booking->class) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Ver Clase
                                </a>

                                @if($booking->status === 'pendiente' || $booking->status === 'aceptada')
                                    <form method="POST" action="{{ route('student.bookings.cancel', $booking) }}" 
                                          onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times me-1"></i>Cancelar
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === 'completada' && !$booking->review)
                                    <a href="{{ route('student.review.create', $booking) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-star me-1"></i>Valorar
                                    </a>
                                @endif

                                @if($booking->review)
                                    <span class="badge bg-success">
                                        <i class="fas fa-star me-1"></i>Valorada: {{ $booking->review->rating }}/5
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Footer con fecha de creación -->
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Reserva creada el {{ $booking->created_at->format('d/m/Y H:i') }}
                            </small>
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
            <h4 class="text-muted">No tienes reservas</h4>
            <p class="text-muted mb-4">
                Comienza buscando clases y reserva tu primera clase particular.
            </p>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Buscar Clases
            </a>
        </div>
    @endif
</div>
@endsection
