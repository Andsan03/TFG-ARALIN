@extends('layouts.app')

@section('title', 'Gestión de Clases')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                Gestión de Clases
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Clases
                    </h5>
                </div>
                <div class="card-body">
                    @if($classes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Profesor</th>
                                        <th>Categoría</th>
                                        <th>Modalidad</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th>Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classes as $class)
                                        <tr>
                                            <td>{{ $class->id }}</td>
                                            <td>{{ $class->title }}</td>
                                            <td>{{ $class->teacher->name }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($class->category) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $class->modality == 'online' ? 'success' : ($class->modality == 'presencial' ? 'warning' : 'primary') }}">
                                                    {{ ucfirst($class->modality) }}
                                                </span>
                                            </td>
                                            <td>{{ $class->price_per_hour }}€/h</td>
                                            <td>
                                                @if($class->is_active)
                                                    <span class="badge bg-success">Activa</span>
                                                @else
                                                    <span class="badge bg-danger">Inactiva</span>
                                                @endif
                                            </td>
                                            <td>{{ $class->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.classes.delete', $class->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta clase? Esta acción no se puede deshacer.')">
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
                            {{ $classes->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay clases registradas en el sistema.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
