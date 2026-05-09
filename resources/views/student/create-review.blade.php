@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- BREADCRUMB --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.bookings') }}" class="text-decoration-none">Mis reservas</a>
                    </li>
                    <li class="breadcrumb-item active">Valorar clase</li>
                </ol>
            </nav>

            {{-- CABECERA --}}
            <div class="text-center mb-4">
                <div class="login-logo mb-3 mx-auto">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="fw-bold mb-1">Valora tu clase</h3>
                <p class="text-muted small">Tu opinión ayuda a otros alumnos a elegir mejor</p>
            </div>

            {{-- TARJETA --}}
            <div class="login-card">

                {{-- Info de la clase --}}
                <div class="d-flex align-items-center bg-light rounded-3 p-3 mb-4">
                    @if($booking->class->teacher->profile_photo)
                        <img src="{{ asset('storage/' . $booking->class->teacher->profile_photo) }}"
                             class="rounded-circle me-3" width="48" height="48" alt="Profesor">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                             style="width:48px;height:48px;font-size:1.1rem">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold small">{{ $booking->class->title }}</div>
                        <div class="text-muted" style="font-size:.8rem">
                            {{ $booking->class->teacher->name }} •
                            {{ $booking->scheduled_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('student.review.store', $booking) }}" method="POST">
                    @csrf

                    {{-- ESTRELLAS --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small d-block mb-2">Puntuación</label>
                        <div class="rating-stars">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                                <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- COMENTARIO --}}
                    <div class="mb-4">
                        <label for="comment" class="form-label fw-semibold small">
                            Comentario <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <textarea id="comment" name="comment"
                                  class="form-control @error('comment') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Comparte tu experiencia: cómo fue el profesor, qué aprendiste...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">
                            <i class="fas fa-paper-plane me-2"></i>Enviar valoración
                        </button>
                        <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection