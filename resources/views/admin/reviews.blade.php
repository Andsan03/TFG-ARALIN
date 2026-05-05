@extends('layouts.app')

@section('title', 'Gestión de Reseñas')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-star text-primary me-2"></i>
                Gestión de Reseñas
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Reseñas
                    </h5>
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Alumno</th>
                                        <th>Profesor</th>
                                        <th>Clase</th>
                                        <th>Valoración</th>
                                        <th>Comentario</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{ $review->id }}</td>
                                            <td>{{ $review->booking->student->name }}</td>
                                            <td>{{ $review->booking->class->teacher->name }}</td>
                                            <td>{{ $review->booking->class->title }}</td>
                                            <td>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <small class="text-muted">({{ $review->rating }}/5)</small>
                                            </td>
                                            <td>
                                                @if(!empty($review->comment))
                                                    <span class="text-muted">Sin comentario</span>
                                                @else
                                                    <span title="{{ $review->comment }}">
                                                        {{ Str::limit($review->comment, 50) }}
                                                        @if(Str::length($review->comment) > 50)...
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta reseña? Esta acción no se puede deshacer.')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay reseñas registradas en el sistema.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
