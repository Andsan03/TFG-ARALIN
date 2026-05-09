@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Explorar clases</h3>
            <p class="text-muted mb-0">Descubre las mejores clases disponibles para ti</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary d-none d-md-inline-block">
            <i class="fas fa-tachometer-alt me-2"></i>Mi Dashboard
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="search-filters bg-white border rounded-3 p-4 mb-4">
        <form method="GET" action="{{ route('student.search') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">¿Qué quieres aprender?</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}" placeholder="Buscar clases...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Categoría</label>
                    <select class="form-select" name="category">
                        <option value="">Todas</option>
                        <option value="matematicas"  {{ request('category') == 'matematicas'  ? 'selected' : '' }}>Matemáticas</option>
                        <option value="ciencias"     {{ request('category') == 'ciencias'     ? 'selected' : '' }}>Ciencias</option>
                        <option value="idiomas"      {{ request('category') == 'idiomas'      ? 'selected' : '' }}>Idiomas</option>
                        <option value="arte"         {{ request('category') == 'arte'         ? 'selected' : '' }}>Arte</option>
                        <option value="musica"       {{ request('category') == 'musica'       ? 'selected' : '' }}>Música</option>
                        <option value="deporte"      {{ request('category') == 'deporte'      ? 'selected' : '' }}>Deporte</option>
                        <option value="programacion" {{ request('category') == 'programacion' ? 'selected' : '' }}>Programación</option>
                        <option value="negocios"     {{ request('category') == 'negocios'     ? 'selected' : '' }}>Negocios</option>
                        <option value="otros"        {{ request('category') == 'otros'        ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Nivel</label>
                    <select class="form-select" name="level">
                        <option value="">Todos</option>
                        <option value="beginner"     {{ request('level') == 'beginner'     ? 'selected' : '' }}>Principiante</option>
                        <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                        <option value="advanced"     {{ request('level') == 'advanced'     ? 'selected' : '' }}>Avanzado</option>
                        <option value="all"          {{ request('level') == 'all'          ? 'selected' : '' }}>Todos los niveles</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Modalidad</label>
                    <select class="form-select" name="modality">
                        <option value="">Todas</option>
                        <option value="online"      {{ request('modality') == 'online'      ? 'selected' : '' }}>Online</option>
                        <option value="presencial"  {{ request('modality') == 'presencial'  ? 'selected' : '' }}>Presencial</option>
                        <option value="ambas"       {{ request('modality') == 'ambas'       ? 'selected' : '' }}>Ambas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Precio máx. (€/h)</label>
                    <input type="number" class="form-control" name="max_price"
                           value="{{ request('max_price') }}" placeholder="Ej: 30">
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
                <a href="{{ route('student.search') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- RESULTADOS --}}
    @if($classes->count() > 0)

        <p class="text-muted small mb-3">
            {{ $classes->total() }} clases encontradas
        </p>

        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <div class="class-card bg-white border rounded-3 h-100">

                        {{-- Cabecera de la tarjeta --}}
                        <div class="class-card-top p-3 border-bottom d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1 me-2">
                                <h6 class="fw-bold mb-1">{{ $class->title }}</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $class->category }}
                                </span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary fs-5">€{{ number_format($class->price_per_hour, 2) }}</div>
                                <div class="text-muted" style="font-size:.75rem">/hora</div>
                            </div>
                        </div>

                        <div class="p-3">

                            {{-- Profesor --}}
                            <div class="d-flex align-items-center mb-3">
                                @if($class->teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}"
                                         class="rounded-circle me-2" width="36" height="36" alt="Profesor">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2"
                                         style="width:36px;height:36px;font-size:.9rem">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold small">{{ $class->teacher->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">Profesor</div>
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <p class="text-muted small mb-3">
                                {{ Str::limit($class->description, 100) }}
                            </p>

                            {{-- Badges nivel y modalidad --}}
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-signal me-1"></i>
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

                            {{-- Rating --}}
                            <div class="d-flex align-items-center justify-content-between mb-3 small text-muted">
                                <div>
                                    @if($class->reviews->count() > 0)
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span class="fw-semibold text-dark">{{ number_format($class->reviews->avg('rating'), 1) }}</span>
                                        <span>({{ $class->reviews->count() }} reseñas)</span>
                                    @else
                                        <i class="fas fa-star text-muted me-1"></i>
                                        <span>Sin reseñas aún</span>
                                    @endif
                                </div>
                                <div>
                                    <i class="fas fa-calendar-check me-1"></i>
                                    {{ $class->bookings->count() }} reservas
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.class.show', $class) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Ver detalles
                                </a>
                                <a href="{{ route('student.book.create', $class) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-calendar-plus me-1"></i>Reservar clase
                                </a>
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

        {{-- Sin resultados --}}
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron clases</h5>
            <p class="text-muted small mb-4">Prueba a ajustar los filtros o busca con otras palabras.</p>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-redo me-2"></i>Ver todas las clases
            </a>
        </div>

    @endif

</div>

@endsection