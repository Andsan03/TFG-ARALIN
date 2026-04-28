@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="fas fa-book me-2"></i>Mis Clases
        </h3>
        <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Clase
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($classes->count() > 0)
        <div class="row">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($class->is_active)
                            <div class="card-header bg-success text-white">
                                <small class="fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Activa
                                </small>
                            </div>
                        @else
                            <div class="card-header bg-secondary text-white">
                                <small class="fw-bold">
                                    <i class="fas fa-pause-circle me-1"></i>Inactiva
                                </small>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $class->title }}</h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($class->description, 100) }}
                            </p>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary me-1">{{ $class->category }}</span>
                                <span class="badge bg-info me-1">{{ $class->level }}</span>
                                <span class="badge bg-warning text-dark">
                                    {{ $class->modality === 'online' ? 'Online' : ($class->modality === 'presential' ? 'Presencial' : 'Mixta') }}
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-primary">€{{ number_format($class->price_per_hour, 2) }}</span>
                                    <small class="text-muted">/hora</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('teacher.classes.edit', $class) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('teacher.classes.toggle', $class) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $class->is_active ? 'warning' : 'success' }}">
                                            <i class="fas fa-{{ $class->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.classes.destroy', $class) }}" class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta clase?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-light">
                            <div class="row text-center small">
                                <div class="col-4">
                                    <div class="fw-bold">{{ $class->bookings->count() }}</div>
                                    <div class="text-muted">Reservas</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold">{{ $class->bookings->where('status', 'aceptada')->count() }}</div>
                                    <div class="text-muted">Aceptadas</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold">{{ $class->reviews->count() }}</div>
                                    <div class="text-muted">Reseñas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $classes->links() }}
        </div>
    @else
        <!-- Mensaje cuando no hay clases -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-chalkboard-teacher fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">Aún no tienes clases creadas</h4>
            <p class="text-muted mb-4">
                Comienza creando tu primera clase para que los alumnos puedan reservar tus servicios.
            </p>
            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Crear tu Primera Clase
            </a>
        </div>
    @endif
</div>
@endsection
