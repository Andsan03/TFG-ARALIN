@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('student.search') }}">Buscar Clases</a>
                </li>
                <li class="breadcrumb-item active">{{ $class->title }}</li>
            </ol>
        </nav>
        <h3 class="mb-1">{{ $class->title }}</h3>
        <p class="text-muted mb-0">{{ $class->category }} • {{ $class->level }}</p>
    </div>

    <div class="row">
        <!-- Columna principal -->
        <div class="col-lg-8">
            <!-- Card principal -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Información del profesor -->
                    <div class="d-flex align-items-center mb-4">
                        @if($class->teacher->profile_photo)
                            <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}" 
                                 class="rounded-circle me-3" width="60" height="60" alt="Profesor">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $class->teacher->name }}</h5>
                            <p class="text-muted mb-0">Profesor Particular</p>
                            @if($averageRating)
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <small class="text-muted">({{ number_format($averageRating, 1) }})</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p>{{ $class->description }}</p>
                    </div>

                    <!-- Detalles de la clase -->
                    <div class="mb-4">
                        <h5>Detalles de la Clase</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Categoría:</strong>
                                <span class="badge bg-primary ms-2">{{ $class->category }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Nivel:</strong>
                                <span class="badge bg-info ms-2">{{ $class->level }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Modalidad:</strong>
                                <span class="badge bg-warning text-dark ms-2">
                                    {{ $class->modality === 'online' ? 'Online' : ($class->modality === 'presential' ? 'Presencial' : 'Mixta') }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Precio:</strong>
                                <span class="badge bg-success text-white ms-2">€{{ number_format($class->price_per_hour, 2) }}/hora</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="fw-bold">{{ $class->bookings->count() }}</div>
                            <div class="text-muted">Reservas</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $class->reviews->count() }}</div>
                            <div class="text-muted">Reseñas</div>
                        </div>
                        <div class="col-4">
                            @if($class->reviews->count() > 0)
                                <div class="fw-bold">{{ number_format($class->reviews->avg('rating'), 1) }}</div>
                                <div class="text-muted">⭐ Rating</div>
                            @else
                                <div class="fw-bold">-</div>
                                <div class="text-muted">Sin rating</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones de reserva -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Reservar Clase
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$hasBooking)
                        <p class="mb-3">
                            ¿Interesado en esta clase? Reserva tu sesión ahora.
                        </p>
                        <div class="d-grid">
                            <a href="{{ route('student.book.create', $class) }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Reservar Clase
                            </a>
                        </div>
                    @else
                        <p class="mb-3">
                            Ya tienes una reserva activa para esta clase. 
                            Puedes ver el estado en <a href="{{ route('student.bookings') }}">Mis Reservas</a>.
                        </p>
                        <a href="{{ route('student.bookings') }}" class="btn btn-success">
                            <i class="fas fa-eye me-2"></i>Ver Mis Reservas
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-lg-4">
            <!-- Card de precio -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-2">€{{ number_format($class->price_per_hour, 2) }}</h3>
                    <p class="text-muted">por hora</p>
                    <hr>
                    <div class="text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check text-success me-2"></i>Clase particular</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check text-success me-2"></i>Profesor verificado</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check text-success me-2"></i>Cancelación gratuita</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de acciones -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Acciones rápidas</h6>
                    <div class="d-grid gap-2">
                        @if(!$isFavorite)
                            <form method="POST" action="{{ route('student.favorites.add', $class->teacher) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-heart me-2"></i>Añadir a favoritos
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('student.favorites.remove', $class->teacher) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-heart me-2"></i>En favoritos
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('student.search') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-search me-2"></i>Buscar más clases
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card de reseñas recientes -->
            @if($class->reviews->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Reseñas recientes</h6>
                    </div>
                    <div class="card-body">
                        @foreach($class->reviews->take(3) as $review)
                            <div class="mb-3 @if(!$loop->last) pb-3 border-bottom @endif">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong>{{ $review->student->name }}</strong>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="small text-muted mb-0">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
