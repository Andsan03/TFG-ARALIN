@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('student.search') }}" class="text-decoration-none">Buscar clases</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('student.class.show', $class) }}" class="text-decoration-none">{{ $class->title }}</a>
            </li>
            <li class="breadcrumb-item active">Reservar</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- COLUMNA PRINCIPAL --}}
        <div class="col-lg-8">

            {{-- EVALUACIÓN DE NIVEL --}}
            <div class="bg-white border border-primary border-opacity-25 rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Evaluación de nivel — {{ ucfirst($class->category) }}</h5>
                        <p class="text-muted small mb-0">Recomendado antes de reservar</p>
                    </div>
                </div>
                <p class="text-muted small mb-3">
                    Te recomendamos hacer una evaluación rápida para que el profesor conozca tu nivel
                    en <strong>{{ $class->category }}</strong> antes de la clase.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('assessment.create', $class->category) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-clipboard-list me-2"></i>Hacer evaluación
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-toggle="collapse" data-bs-target="#skipNote">
                        <i class="fas fa-forward me-1"></i>Omitir
                    </button>
                </div>
                <div class="collapse mt-3" id="skipNote">
                    <div class="alert alert-warning small mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Puedes omitir la evaluación, pero el profesor llegará sin información previa sobre tu nivel.
                    </div>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <div class="bg-white border rounded-3 p-4">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-calendar-plus text-primary me-2"></i>Formulario de reserva
                </h5>

                <form method="POST" action="{{ route('student.book', $class) }}">
                    @csrf

                    {{-- Resumen de la clase --}}
                    <div class="d-flex align-items-center bg-light rounded-3 p-3 mb-4">
                        @if($class->teacher->profile_photo)
                            <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}"
                                 class="rounded-circle me-3" width="48" height="48" alt="Profesor">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                 style="width:48px;height:48px;font-size:1.1rem">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $class->title }}</div>
                            <div class="text-muted small">{{ $class->teacher->name }}</div>
                        </div>
                        <div class="text-primary fw-bold">€{{ number_format($class->price_per_hour, 2) }}/h</div>
                    </div>

                    {{-- Modalidad (solo si es ambas) --}}
                    @if($class->modality === 'ambas')
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Modalidad de la clase</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="booking_modality"
                                           id="mod-online" value="online" required>
                                    <label class="btn btn-outline-primary w-100" for="mod-online">
                                        <i class="fas fa-video d-block mb-1"></i>Online
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="booking_modality"
                                           id="mod-presencial" value="presencial" required>
                                    <label class="btn btn-outline-primary w-100" for="mod-presencial">
                                        <i class="fas fa-map-marker-alt d-block mb-1"></i>Presencial
                                    </label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="booking_modality" value="{{ $class->modality }}">
                    @endif

                    {{-- Fecha y hora --}}
                    <div class="mb-4">
                        <label for="scheduled_at" class="form-label fw-semibold">
                            Fecha y hora de la clase
                        </label>
                        <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror"
                               id="scheduled_at" name="scheduled_at"
                               required min="{{ now()->format('Y-m-d\TH:i') }}"
                               value="{{ old('scheduled_at') }}">
                        <div class="form-text">Selecciona una fecha y hora futuras.</div>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mensaje para el profesor --}}
                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">
                            Mensaje para el profesor <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <textarea class="form-control" id="message" name="message" rows="3"
                                  placeholder="Cuéntale tus objetivos o lo que quieres trabajar en la clase...">{{ old('message') }}</textarea>
                        <div class="form-text">Máximo 500 caracteres.</div>
                    </div>

                    {{-- Términos --}}
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label small text-muted" for="terms">
                                Acepto los <a href="#" class="text-primary">términos y condiciones</a>
                                y la <a href="#" class="text-primary">política de cancelación</a>
                            </label>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-calendar-plus me-2"></i>Confirmar reserva
                        </button>
                        <a href="{{ route('student.class.show', $class) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                    </div>

                </form>
            </div>

        </div>

        {{-- COLUMNA LATERAL --}}
        <div class="col-lg-4">

            {{-- RESUMEN --}}
            <div class="bg-white border rounded-3 p-4 mb-4 sticky-top" style="top: 80px">
                <h6 class="fw-bold mb-3">Resumen de la reserva</h6>

                <div class="mb-2 d-flex justify-content-between small">
                    <span class="text-muted">Clase</span>
                    <span class="fw-semibold text-end" style="max-width:160px">{{ $class->title }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between small">
                    <span class="text-muted">Profesor</span>
                    <span class="fw-semibold">{{ $class->teacher->name }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between small">
                    <span class="text-muted">Categoría</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $class->category }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between small">
                    <span class="text-muted">Nivel</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                        @switch($class->level)
                            @case('beginner') Principiante @break
                            @case('intermediate') Intermedio @break
                            @case('advanced') Avanzado @break
                            @default Todos
                        @endswitch
                    </span>
                </div>
                <div class="mb-3 d-flex justify-content-between small">
                    <span class="text-muted">Modalidad</span>
                    <span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($class->modality) }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Precio</span>
                    <span class="text-primary fw-bold fs-5">€{{ number_format($class->price_per_hour, 2) }}/h</span>
                </div>
            </div>

            {{-- INFORMACIÓN --}}
            <div class="bg-white border rounded-3 p-4">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>A tener en cuenta
                </h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2 d-flex gap-2">
                        <i class="fas fa-clock text-primary mt-1 "></i>
                        La reserva quedará pendiente hasta que el profesor la confirme
                    </li>
                    <li class="mb-2 d-flex gap-2">
                        <i class="fas fa-ban text-primary mt-1 "></i>
                        Puedes cancelar hasta 24 horas antes
                    </li>
                    <li class="mb-2 d-flex gap-2">
                        <i class="fas fa-comment text-primary mt-1 "></i>
                        El profesor contactará contigo para confirmar
                    </li>
                    <li class="d-flex gap-2">
                        <i class="fas fa-eye text-primary mt-1 "></i>
                        Puedes ver el estado en "Mis Reservas"
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>

@endsection