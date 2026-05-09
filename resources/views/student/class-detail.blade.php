@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('student.search') }}" class="text-decoration-none">Buscar clases</a>
            </li>
            <li class="breadcrumb-item active">{{ $class->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- COLUMNA PRINCIPAL --}}
        <div class="col-lg-8">

            {{-- INFO PRINCIPAL --}}
            <div class="bg-white border rounded-3 p-4 mb-4">

                {{-- Título y badges --}}
                <div class="mb-4">
                    <h3 class="fw-bold mb-2">{{ $class->title }}</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $class->category }}</span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                            @switch($class->level)
                                @case('beginner') Principiante @break
                                @case('intermediate') Intermedio @break
                                @case('advanced') Avanzado @break
                                @default Todos los niveles
                            @endswitch
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="fas fa-{{ $class->modality === 'online' ? 'video' : ($class->modality === 'presencial' ? 'map-marker-alt' : 'globe') }} me-1"></i>
                            {{ ucfirst($class->modality) }}
                        </span>
                    </div>
                </div>

                {{-- Profesor --}}
                <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-4">
                    @if($class->teacher->profile_photo)
                        <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}"
                             class="rounded-circle me-3" width="56" height="56" alt="Profesor">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                             style="width:56px;height:56px;font-size:1.3rem">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $class->teacher->name }}</div>
                        <div class="text-muted small">Profesor particular</div>
                        @if($averageRating)
                            <div class="text-warning small mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa{{ $i <= round($averageRating) ? 's' : 'r' }} fa-star"></i>
                                @endfor
                                <span class="text-muted ms-1">{{ number_format($averageRating, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-2">Descripción</h5>
                    <p class="text-muted">{{ $class->description }}</p>
                </div>

                {{-- Estadísticas --}}
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="stat-box bg-light rounded-3 p-3">
                            <div class="fw-bold fs-5">{{ $class->bookings->count() }}</div>
                            <div class="text-muted small">Reservas</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box bg-light rounded-3 p-3">
                            <div class="fw-bold fs-5">{{ $class->reviews->count() }}</div>
                            <div class="text-muted small">Reseñas</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box bg-light rounded-3 p-3">
                            @if($class->reviews->count() > 0)
                                <div class="fw-bold fs-5 text-warning">{{ number_format($class->reviews->avg('rating'), 1) }}</div>
                                <div class="text-muted small">⭐ Rating</div>
                            @else
                                <div class="fw-bold fs-5 text-muted">—</div>
                                <div class="text-muted small">Sin rating</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- RESEÑAS --}}
            @if($class->reviews->count() > 0)
                <div class="bg-white border rounded-3 p-4">
                    <h5 class="fw-bold mb-3">Reseñas recientes</h5>
                    @foreach($class->reviews->take(4) as $review)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold small">{{ $review->student->name }}</span>
                                <span class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </span>
                            </div>
                            @if($review->comment)
                                <p class="text-muted small mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- COLUMNA LATERAL --}}
        <div class="col-lg-4">

            {{-- PRECIO Y RESERVA --}}
            <div class="bg-white border rounded-3 p-4 mb-4 sticky-top" style="top: 80px">

                {{-- Precio --}}
                <div class="text-center mb-4">
                    <div class="text-primary fw-bold" style="font-size: 2.2rem">
                        €{{ number_format($class->price_per_hour, 2) }}
                    </div>
                    <div class="text-muted small">por hora</div>
                </div>

                {{-- Incluye --}}
                <ul class="list-unstyled mb-4">
                    <li class="mb-2 small">
                        <i class="fas fa-check text-success me-2"></i>Clase particular
                    </li>
                    <li class="mb-2 small">
                        <i class="fas fa-check text-success me-2"></i>Profesor verificado
                    </li>
                    <li class="mb-2 small">
                        <i class="fas fa-check text-success me-2"></i>Cancelación gratuita
                    </li>
                    @if($class->modality !== 'presencial')
                        <li class="mb-2 small">
                            <i class="fas fa-check text-success me-2"></i>Enlace de videollamada incluido
                        </li>
                    @endif
                </ul>

                {{-- Botón reservar --}}
                @if(!$hasBooking)
                    <div class="d-grid mb-3">
                        <a href="{{ route('student.book.create', $class) }}" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-calendar-plus me-2"></i>Reservar clase
                        </a>
                    </div>
                @else
                    <div class="d-grid mb-3">
                        <a href="{{ route('student.bookings') }}" class="btn btn-success btn-lg fw-bold">
                            <i class="fas fa-check me-2"></i>Ya tienes una reserva
                        </a>
                    </div>
                @endif

                {{-- Favorito --}}
                @if(!$isFavorite)
                    <form method="POST" action="{{ route('student.favorites.add', $class->teacher) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-heart me-2"></i>Añadir a favoritos
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('student.favorites.remove', $class->teacher) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-heart me-2"></i>En favoritos
                        </button>
                    </form>
                @endif

            </div>

        </div>
    </div>
</div>

@endsection