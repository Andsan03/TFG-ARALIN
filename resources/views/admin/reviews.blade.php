@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de reseñas</h3>
            <p class="text-muted mb-0">Modera y elimina reseñas inapropiadas</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
            <i class="fas fa-arrow-left me-1"></i>Dashboard
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="search-filters bg-white border rounded-3 p-4 mb-4">
        <form method="GET" action="{{ route('admin.reviews') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}"
                               placeholder="Buscar por alumno, profesor o clase...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="rating">
                        <option value="">Todas las valoraciones</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 estrellas</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 estrellas</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 estrellas</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 estrellas</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 estrella</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                    <a href="{{ route('admin.reviews') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLA --}}
    @if($reviews->count() > 0)

        <p class="text-muted small mb-3">{{ $reviews->total() }} reseñas encontradas</p>

        <div class="bg-white border rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold px-4">Alumno</th>
                            <th class="small fw-semibold">Profesor</th>
                            <th class="small fw-semibold">Clase</th>
                            <th class="small fw-semibold">Valoración</th>
                            <th class="small fw-semibold">Comentario</th>
                            <th class="small fw-semibold">Fecha</th>
                            <th class="small fw-semibold">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                {{-- Alumno --}}
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($review->student->profile_photo)
                                            <img src="{{ asset('storage/' . $review->student->profile_photo) }}"
                                                 class="rounded-circle" width="28" height="28" alt="">
                                        @else
                                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold"
                                                 style="width:28px;height:28px;font-size:.75rem">
                                                {{ substr($review->student->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="small fw-semibold">{{ $review->student->name }}</span>
                                    </div>
                                </td>

                                {{-- Profesor --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($review->teacher->profile_photo)
                                            <img src="{{ asset('storage/' . $review->teacher->profile_photo) }}"
                                                 class="rounded-circle" width="28" height="28" alt="">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                 style="width:28px;height:28px;font-size:.75rem">
                                                {{ substr($review->teacher->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="small">{{ $review->teacher->name }}</span>
                                    </div>
                                </td>

                                {{-- Clase --}}
                                <td class="small text-muted">
                                    {{ Str::limit($review->booking->class->title, 25) }}
                                </td>

                                {{-- Valoración --}}
                                <td>
                                    <div class="text-warning" style="font-size:.8rem">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem">{{ $review->rating }}/5</div>
                                </td>

                                {{-- Comentario --}}
                                <td class="small text-muted" style="max-width:200px">
                                    @if($review->comment)
                                        <span title="{{ $review->comment }}">
                                            {{ Str::limit($review->comment, 50) }}
                                        </span>
                                    @else
                                        <span class="fst-italic">Sin comentario</span>
                                    @endif
                                </td>

                                {{-- Fecha --}}
                                <td class="small text-muted">
                                    {{ $review->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Eliminar --}}
                                <td>
                                    <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST"
                                          onsubmit="return confirm('¿Seguro que quieres eliminar esta reseña?')">
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
            {{ $reviews->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-star fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron reseñas</h5>
            <a href="{{ route('admin.reviews') }}" class="btn btn-outline-primary mt-3">
                <i class="fas fa-times me-1"></i>Limpiar filtros
            </a>
        </div>
    @endif

</div>

@endsection