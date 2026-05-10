@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de clases</h3>
            <p class="text-muted mb-0">Modera y elimina anuncios de la plataforma</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
            <i class="fas fa-arrow-left me-1"></i>Dashboard
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="search-filters bg-white border rounded-3 p-4 mb-4">
        <form method="GET" action="{{ route('admin.classes') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}" placeholder="Buscar por título o profesor...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="category">
                        <option value="">Categoría</option>
                        <option value="matematicas"  {{ request('category') == 'matematicas'  ? 'selected' : '' }}>Matemáticas</option>
                        <option value="ciencias"     {{ request('category') == 'ciencias'     ? 'selected' : '' }}>Ciencias</option>
                        <option value="idiomas"      {{ request('category') == 'idiomas'      ? 'selected' : '' }}>Idiomas</option>
                        <option value="musica"       {{ request('category') == 'musica'       ? 'selected' : '' }}>Música</option>
                        <option value="programacion" {{ request('category') == 'programacion' ? 'selected' : '' }}>Programación</option>
                        <option value="otros"        {{ request('category') == 'otros'        ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="modality">
                        <option value="">Modalidad</option>
                        <option value="online"    {{ request('modality') == 'online'    ? 'selected' : '' }}>Online</option>
                        <option value="presencial" {{ request('modality') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                        <option value="ambas"     {{ request('modality') == 'ambas'     ? 'selected' : '' }}>Ambas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Estado</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Activas</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivas</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                    <a href="{{ route('admin.classes') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLA --}}
    @if($classes->count() > 0)

        <p class="text-muted small mb-3">{{ $classes->total() }} clases encontradas</p>

        <div class="bg-white border rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold px-4">Clase</th>
                            <th class="small fw-semibold">Profesor</th>
                            <th class="small fw-semibold">Categoría</th>
                            <th class="small fw-semibold">Modalidad</th>
                            <th class="small fw-semibold">Precio</th>
                            <th class="small fw-semibold">Estado</th>
                            <th class="small fw-semibold">Creación</th>
                            <th class="small fw-semibold">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                            <tr>
                                {{-- Clase --}}
                                <td class="px-4">
                                    <div class="fw-semibold small">{{ Str::limit($class->title, 35) }}</div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        {{ $class->bookings->count() }} reservas
                                    </div>
                                </td>

                                {{-- Profesor --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($class->teacher->profile_photo)
                                            <img src="{{ asset('storage/' . $class->teacher->profile_photo) }}"
                                                 class="rounded-circle" width="28" height="28" alt="">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                 style="width:28px;height:28px;font-size:.75rem">
                                                {{ substr($class->teacher->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="small">{{ $class->teacher->name }}</span>
                                    </div>
                                </td>

                                {{-- Categoría --}}
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ ucfirst($class->category) }}
                                    </span>
                                </td>

                                {{-- Modalidad --}}
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-{{ $class->modality === 'online' ? 'video' : ($class->modality === 'presencial' ? 'map-marker-alt' : 'globe') }} me-1"></i>
                                        {{ ucfirst($class->modality) }}
                                    </span>
                                </td>

                                {{-- Precio --}}
                                <td class="small fw-bold text-primary">
                                    €{{ number_format($class->price_per_hour, 2) }}/h
                                </td>

                                {{-- Estado --}}
                                <td>
                                    <span class="badge {{ $class->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $class->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>

                                {{-- Fecha --}}
                                <td class="small text-muted">
                                    {{ $class->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Eliminar --}}
                                <td>
                                    <form action="{{ route('admin.classes.delete', $class->id) }}" method="POST"
                                          onsubmit="return confirm('¿Seguro que quieres eliminar {{ addslashes($class->title) }}? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $classes->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-book fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron clases</h5>
            <a href="{{ route('admin.classes') }}" class="btn btn-outline-primary mt-3">
                <i class="fas fa-times me-1"></i>Limpiar filtros
            </a>
        </div>
    @endif

</div>

@endsection