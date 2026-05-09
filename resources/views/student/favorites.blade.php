@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Profesores favoritos</h3>
            <p class="text-muted mb-0">Tus profesores guardados</p>
        </div>
        <a href="{{ route('student.search') }}" class="btn btn-primary d-none d-md-inline-block">
            <i class="fas fa-search me-2"></i>Buscar clases
        </a>
    </div>

    @if($favoriteTeachers->count() > 0)

        <p class="text-muted small mb-3">{{ $favoriteTeachers->count() }} profesores guardados</p>

        <div class="row g-4">
            @foreach($favoriteTeachers as $teacher)
                <div class="col-md-6 col-lg-4">
                    <div class="teacher-card bg-white border rounded-3 h-100">
                        <div class="p-4 text-center">

                            {{-- Foto --}}
                            @if($teacher->profile_photo)
                                <img src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                     class="rounded-circle mb-3"
                                     width="80" height="80" alt="{{ $teacher->name }}">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mb-3 mx-auto"
                                     style="width:80px;height:80px;font-size:1.8rem">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif

                            {{-- Nombre --}}
                            <h6 class="fw-bold mb-1">{{ $teacher->name }}</h6>

                            {{-- Rating --}}
                            @if($teacher->reviewsReceived->count() > 0)
                                <div class="mb-2">
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= round($teacher->reviewsReceived->avg('rating')) ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                    </span>
                                    <small class="text-muted ms-1">
                                        ({{ $teacher->reviewsReceived->count() }} valoraciones)
                                    </small>
                                </div>
                            @else
                                <small class="text-muted d-block mb-2">Sin valoraciones aún</small>
                            @endif

                            {{-- Especialidades --}}
                            @if($teacher->classes->isNotEmpty())
                                <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                                    @foreach($teacher->classes->pluck('category')->unique()->take(3) as $category)
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $category }}</span>
                                    @endforeach
                                    @if($teacher->classes->pluck('category')->unique()->count() > 3)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            +{{ $teacher->classes->pluck('category')->unique()->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Precio promedio --}}
                            @if($teacher->classes->isNotEmpty())
                                <div class="mb-3">
                                    <span class="fw-bold text-primary fs-5">
                                        €{{ number_format($teacher->classes->avg('price_per_hour'), 2) }}
                                    </span>
                                    <span class="text-muted small">/hora promedio</span>
                                </div>
                            @endif

                        </div>

                        {{-- Botones --}}
                        <div class="border-top p-3 d-grid gap-2">
                            <a href="{{ route('student.search', ['teacher' => $teacher->id]) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i>Ver clases
                            </a>
                            <form action="{{ route('student.favorites.remove', $teacher) }}" method="POST"
                                  onsubmit="return confirm('¿Quieres eliminar a {{ $teacher->name }} de favoritos?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-heart-broken me-1"></i>Eliminar de favoritos
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    @else

        <div class="text-center py-5">
            <i class="fas fa-heart fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Aún no tienes profesores favoritos</h5>
            <p class="text-muted small mb-4">
                Explora las clases y guarda los profesores que más te gusten.
            </p>
            <a href="{{ route('student.search') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Explorar clases
            </a>
        </div>

    @endif

</div>

@endsection