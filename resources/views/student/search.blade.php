@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Explorar Clases</h3>
            <p class="text-muted mb-0">Descubre las mejores clases disponibles para ti</p>
        </div>
        <div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-tachometer-alt me-2"></i>Mi Dashboard
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('student.search') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="¿Qué quieres aprender?">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="level" class="form-label">Nivel</label>
                        <select class="form-select" id="level" name="level">
                            <option value="">Todos los niveles</option>
                            <option value="principiante" {{ request('level') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ request('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ request('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            <option value="experto" {{ request('level') == 'experto' ? 'selected' : '' }}>Experto</option>
                            <option value="todos" {{ request('level') == 'todos' ? 'selected' : '' }}>Todos los niveles</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="modality" class="form-label">Modalidad</label>
                        <select class="form-select" id="modality" name="modality">
                            <option value="">Todas</option>
                            <option value="online" {{ request('modality') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="presential" {{ request('modality') == 'presential' ? 'selected' : '' }}>Presencial</option>
                            <option value="mixed" {{ request('modality') == 'mixed' ? 'selected' : '' }}>Mixta</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Buscar Clases
                        </button>
                        <a href="{{ route('student.search') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if($classes->count() > 0)
        <div class="row">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Header con precio -->
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $class->title }}</h5>
                                <div class="text-end">
                                    <span class="fw-bold">€{{ number_format($class->price_per_hour, 2) }}</span>
                                    <small class="d-block">/hora</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Información del profesor -->
                            <div class="d-flex align-items-center mb-3">
                                @if($class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}" 
                                         class="rounded-circle me-3" width="40" height="40" alt="Profesor">
                                @else
                                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $class->teacher->name }}</h6>
                                    <small class="text-muted">Profesor</small>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($class->description, 120) }}
                            </p>

                            <!-- Badges -->
                            <div class="mb-3">
                                <span class="badge bg-primary me-1">{{ $class->category }}</span>
                                <span class="badge bg-info me-1">{{ $class->level }}</span>
                                <span class="badge bg-warning text-dark">
                                    {{ $class->modality === 'online' ? 'Online' : ($class->modality === 'presential' ? 'Presencial' : 'Mixta') }}
                                </span>
                            </div>

                            <!-- Estadísticas -->
                            <div class="row text-center small mb-3">
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

                            <!-- Acciones -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.class.show', $class) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Ver Detalles
                                </a>
                                <form method="POST" action="{{ route('student.book', $class) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Reservar Clase
                                    </button>
                                </form>
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
        <!-- Mensaje cuando no hay resultados -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">No se encontraron clases</h4>
            <p class="text-muted mb-4">
                Intenta ajustar los filtros o busca con diferentes palabras clave.
            </p>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-redo me-2"></i>Ver Todas las Clases
            </a>
        </div>
    @endif
</div>
@endsection
