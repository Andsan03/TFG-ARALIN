@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.search') }}">Buscar Clases</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.class.show', $class) }}">{{ $class->title }}</a>
                    </li>
                    <li class="breadcrumb-item active">Reservar Clase</li>
                </ol>
            </nav>
            <h3 class="mb-1">Reservar Clase</h3>
            <p class="text-muted mb-0">{{ $class->title }} • {{ $class->teacher->name }}</p>
        </div>
        <div>
            <a href="{{ route('student.class.show', $class) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a Detalles
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Columna principal - Formulario -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Formulario de Reserva
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.book', $class) }}">
                        @csrf
                        
                        <!-- Información de la clase -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                @if($class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}" 
                                         class="rounded-circle me-3" width="50" height="50" alt="Profesor">
                                @else
                                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $class->title }}</h6>
                                    <p class="text-muted mb-0">{{ $class->teacher->name }} • €{{ number_format($class->price_per_hour, 2) }}/hora</p>
                                </div>
                            </div>
                        </div>

                        <!-- Modalidad (solo para clases mixtas) -->
                        @if($class->modality === 'mixta')
                        <div class="mb-4">
                            <label for="booking_modality" class="form-label">
                                <i class="fas fa-exchange-alt me-2"></i>Modalidad de la clase
                            </label>
                            <select class="form-select" id="booking_modality" name="booking_modality" required>
                                <option value="">Selecciona una modalidad</option>
                                <option value="online">Online</option>
                                <option value="presential">Presencial</option>
                            </select>
                            <small class="form-text text-muted">
                                Esta clase admite ambas modalidades. Elige la que prefieras.
                            </small>
                        </div>
                        @else
                        <!-- Para clases online o presenciales, guardar la modalidad automáticamente -->
                        <input type="hidden" name="booking_modality" value="{{ $class->modality }}">
                        @endif

                        <!-- Fecha y hora -->
                        <div class="mb-4">
                            <label for="scheduled_at" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha y hora de la clase
                            </label>
                            <input type="datetime-local" class="form-control" id="scheduled_at" 
                                   name="scheduled_at" required min="{{ now()->format('Y-m-d\TH:i') }}">
                            <small class="form-text text-muted">
                                Selecciona una fecha y hora futuras para tu clase
                            </small>
                        </div>

                        <!-- Mensaje para el profesor -->
                        <div class="mb-4">
                            <label for="message" class="form-label">
                                <i class="fas fa-comment me-2"></i>Mensaje para el profesor (opcional)
                            </label>
                            <textarea class="form-control" id="message" name="message" rows="4" 
                                      placeholder="Escribe algo sobre ti, tus objetivos o lo que quieres aprender en esta clase..."></textarea>
                            <small class="form-text text-muted">
                                Máximo 500 caracteres
                            </small>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="#" class="text-primary">términos y condiciones</a> 
                                    y la <a href="#" class="text-primary">política de cancelación</a>
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Confirmar Reserva
                            </button>
                            <a href="{{ route('student.class.show', $class) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna lateral - Resumen -->
        <div class="col-lg-4">
            <!-- Card de resumen -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Resumen de la Reserva</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Clase:</strong>
                        <p class="mb-1">{{ $class->title }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Profesor:</strong>
                        <p class="mb-1">{{ $class->teacher->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Categoría:</strong>
                        <p class="mb-1">
                            <span class="badge bg-primary">{{ $class->category }}</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>Nivel:</strong>
                        <p class="mb-1">
                            <span class="badge bg-info">{{ $class->level }}</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>Modalidad:</strong>
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark">
                                {{ $class->modality === 'online' ? 'Online' : ($class->modality === 'presential' ? 'Presencial' : 'Mixta') }}
                            </span>
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Precio:</strong>
                        <p class="mb-1 text-primary fw-bold">€{{ number_format($class->price_per_hour, 2) }}/hora</p>
                    </div>
                </div>
            </div>

            <!-- Card de información -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">La reserva quedará pendiente hasta que el profesor la confirme</li>
                        <li class="mb-2">Podrás cancelar la reserva hasta 24 horas antes</li>
                        <li class="mb-2">El profesor contactará contigo para confirmar los detalles</li>
                        <li class="mb-0">Podrás ver el estado de tu reserva en "Mis Reservas"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
