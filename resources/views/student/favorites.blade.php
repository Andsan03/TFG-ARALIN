@extends('layouts.app')

@section('title', 'Mis Profesores Favoritos')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Profesores Favoritos</li>
                </ol>
            </nav>

            <!-- Tarjeta principal -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-heart me-2"></i>Mis Profesores Favoritos
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($favoriteTeachers->count() > 0)
                        <!-- Lista de profesores favoritos -->
                        <div class="row">
                            @foreach($favoriteTeachers as $teacher)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <!-- Foto del profesor -->
                                            @if($teacher->profile_photo)
                                                <img src="{{ asset('storage/' . $teacher->profile_photo) }}" 
                                                     alt="{{ $teacher->name }}" 
                                                     class="rounded-circle mb-3" 
                                                     width="80" height="80">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-user fa-2x"></i>
                                                </div>
                                            @endif

                                            <!-- Información del profesor -->
                                            <h6 class="card-title mb-2">{{ $teacher->name }}</h6>
                                            
                                            <!-- Rating promedio -->
                                            @if($teacher->reviews->count() > 0)
                                                <div class="mb-2">
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= round($teacher->reviews->avg('rating')))
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">
                                                        ({{ $teacher->reviews->count() }} {{ $teacher->reviews->count() == 1 ? 'valoración' : 'valoraciones' }})
                                                    </small>
                                                </div>
                                            @endif

                                            <!-- Especialidades -->
                                            @if($teacher->classes->isNotEmpty())
                                                <div class="mb-3">
                                                    <small class="text-muted">Especialidades:</small>
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        @foreach($teacher->classes->pluck('category')->unique()->take(3) as $category)
                                                            <span class="badge bg-light text-dark">{{ $category }}</span>
                                                        @endforeach
                                                        @if($teacher->classes->count() > 3)
                                                            <span class="badge bg-secondary">+{{ $teacher->classes->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Precio promedio -->
                                            @if($teacher->classes->isNotEmpty())
                                                <div class="mb-3">
                                                    <small class="text-muted">Precio promedio:</small>
                                                    <div class="fw-bold text-primary">
                                                        €{{ number_format($teacher->classes->avg('price_per_hour'), 2) }}/h
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Botones de acción -->
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('student.search', ['teacher' => $teacher->id]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-search me-1"></i>Ver Clases
                                                </a>
                                                <form action="{{ route('student.favorites.remove', $teacher) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('¿Estás seguro de que quieres eliminar a {{ $teacher->name }} de tus favoritos?')">
                                                        <i class="fas fa-heart-broken me-1"></i>Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Mensaje cuando no hay favoritos -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-heart fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aún no tienes profesores favoritos</h5>
                            <p class="text-muted mb-4">
                                Explora las clases disponibles y añade profesores a tu lista de favoritos para acceder rápidamente a ellos.
                            </p>
                            <a href="{{ route('student.search') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Explorar Clases
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-size: 0.75rem;
}

.d-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .d-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
