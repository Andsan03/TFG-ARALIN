@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Mis clases</h3>
            <p class="text-muted mb-0">Gestiona y publica tus clases particulares</p>
        </div>
        <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva clase
        </a>
    </div>

    @if($classes->count() > 0)

        <p class="text-muted small mb-3">{{ $classes->total() }} clases en total</p>

        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <div class="class-card bg-white border rounded-3 h-100">

                        {{-- Barra de estado --}}
                        <div style="height:4px; border-radius:12px 12px 0 0;
                            background: {{ $class->is_active ? '#198754' : '#6c757d' }}">
                        </div>

                        <div class="p-4">

                            {{-- Estado + precio --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge {{ $class->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas fa-{{ $class->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                    {{ $class->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                                <div class="text-end">
                                    <span class="fw-bold text-primary">€{{ number_format($class->price_per_hour, 2) }}</span>
                                    <span class="text-muted small">/h</span>
                                </div>
                            </div>

                            {{-- Título y descripción --}}
                            <h6 class="fw-bold mb-1">{{ $class->title }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($class->description, 90) }}</p>

                            {{-- Badges --}}
                            <div class="d-flex flex-wrap gap-1 mb-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $class->category }}</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    @switch($class->level)
                                        @case('beginner')     Principiante @break
                                        @case('intermediate') Intermedio   @break
                                        @case('advanced')     Avanzado     @break
                                        @default              Todos        @break
                                    @endswitch
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-{{ $class->modality === 'online' ? 'video' : ($class->modality === 'presencial' ? 'map-marker-alt' : 'globe') }} me-1"></i>
                                    {{ ucfirst($class->modality) }}
                                </span>
                            </div>

                            {{-- Acciones --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('teacher.classes.edit', $class) }}"
                                   class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form method="POST" action="{{ route('teacher.classes.toggle', $class) }}">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $class->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $class->is_active ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas fa-{{ $class->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('teacher.classes.destroy', $class) }}"
                                      onsubmit="return confirm('¿Seguro que quieres eliminar esta clase?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </div>

                        {{-- Stats --}}
                        <div class="border-top px-4 py-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fw-bold small">{{ $class->bookings->count() }}</div>
                                    <div class="text-muted" style="font-size:.72rem">Reservas</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold small">{{ $class->bookings->where('status', 'aceptada')->count() }}</div>
                                    <div class="text-muted" style="font-size:.72rem">Aceptadas</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold small">
                                        @if($class->reviews->count() > 0)
                                            <i class="fas fa-star text-warning" style="font-size:.7rem"></i>
                                            {{ number_format($class->reviews->avg('rating'), 1) }}
                                        @else
                                            —
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem">Valoración</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $classes->links() }}
        </div>

    @else

        <div class="text-center py-5">
            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Aún no tienes clases creadas</h5>
            <p class="text-muted small mb-4">
                Crea tu primera clase para que los alumnos puedan reservar tus servicios.
            </p>
            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear primera clase
            </a>
        </div>

    @endif

</div>

@endsection