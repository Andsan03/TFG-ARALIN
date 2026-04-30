@extends('layouts.app')

@section('title', 'Valorar Clase')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.bookings') }}" class="text-decoration-none">Mis Reservas</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Valorar Clase</li>
                </ol>
            </nav>

            <!-- Tarjeta principal -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>Valorar Clase
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Información de la clase -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($booking->class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $booking->class->teacher->profile_photo) }}" 
                                         alt="{{ $booking->class->teacher->name }}" 
                                         class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $booking->class->title }}</h6>
                                <p class="mb-0 text-muted">
                                    Profesor: {{ $booking->class->teacher->name }} • 
                                    {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de valoración -->
                    <form action="{{ route('student.review.store', $booking) }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Puntuación -->
                            <div class="rating-container">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                                    <label for="star{{ $i }}" class="star">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <!-- Comentario -->
                            <div class="col-12 mb-4">
                                <label for="comment" class="form-label fw-semibold">
                                    <i class="fas fa-comment me-2"></i>Comentario
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="comment" name="comment" class="form-control" rows="4" required
                                          placeholder="Comparte tu experiencia con esta clase...">{{ old('comment') }}</textarea>
                                <small class="form-text text-muted">
                                    Describe tu experiencia, qué aprendiste, cómo fue el profesor, etc.
                                </small>
                                @error('comment')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('student.bookings') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Valoración
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .rating-container {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-start;
    font-size: 1.8rem;
}

.rating-container input {
    display: none;
}

.rating-container label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

/* Hover */
.rating-container label:hover,
.rating-container label:hover ~ label {
    color: #ffc107;
}

/* Selección */
.rating-container input:checked ~ label {
    color: #ffc107;
}
</style>
@endsection
