@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="fas fa-star me-2"></i>Reseñas de mis Clases
        </h3>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="filterReviews('all')">
                Todas
            </button>
            <button type="button" class="btn btn-outline-success" onclick="filterReviews('5')">
                5 ⭐
            </button>
            <button type="button" class="btn btn-outline-warning" onclick="filterReviews('4')">
                4 ⭐
            </button>
            <button type="button" class="btn btn-outline-info" onclick="filterReviews('3')">
                3 ⭐ o menos
            </button>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    @if($reviews->count() > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $reviews->count() }}</h2>
                        <small>Total Reseñas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ number_format($reviews->avg('rating'), 1) }}</h2>
                        <small>Promedio</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $reviews->where('rating', 5)->count() }}</h2>
                        <small>5 Estrellas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $reviews->where('rating', '>=', 4)->count() }}</h2>
                        <small>4+ Estrellas</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($reviews->count() > 0)
        <div class="row">
            @foreach($reviews as $review)
                <div class="col-md-6 col-lg-4 mb-4" data-rating="{{ $review->rating }}">
                    <div class="card h-100 shadow-sm">
                        <!-- Header con rating -->
                        <div class="card-header bg-{{ $review->rating >= 4 ? 'success' : ($review->rating == 3 ? 'warning' : 'danger') }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-white-50' }}"></i>
                                    @endfor
                                    <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                </div>
                                <small>{{ $review->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Información del alumno -->
                            <div class="d-flex align-items-center mb-3">
                                @if($review->student->profile_photo)
                                    <img src="{{ asset('storage/' . $review->student->profile_photo) }}" 
                                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        {{ substr($review->student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $review->student->name }}</h6>
                                    <small class="text-muted">{{ $review->student->email }}</small>
                                </div>
                            </div>

                            <!-- Información de la clase -->
                            <div class="mb-3">
                                <h6 class="text-primary mb-1">{{ $review->booking->class->title }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>{{ $review->booking->class->category }}
                                    <span class="mx-1">•</span>
                                    <i class="fas fa-signal me-1"></i>{{ $review->booking->class->level }}
                                </small>
                            </div>

                            <!-- Comentario -->
                            @if($review->comment)
                                <div class="alert alert-light py-2">
                                    <p class="mb-0 small">{{ $review->comment }}</p>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <small><i class="fas fa-comment-slash me-1"></i>Sin comentario</small>
                                </div>
                            @endif

                            <!-- Fecha de la reserva -->
                            <div class="text-muted small">
                                <i class="fas fa-calendar me-1"></i>
                                Clase realizada: {{ $review->booking->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    @if($review->rating >= 4)
                                        <i class="fas fa-smile text-success me-1"></i>Excelente
                                    @elseif($review->rating == 3)
                                        <i class="fas fa-meh text-warning me-1"></i>Buena
                                    @else
                                        <i class="fas fa-frown text-danger me-1"></i>Necesita mejorar
                                    @endif
                                </small>
                                <small class="text-muted">
                                    #{{ $review->id }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <!-- Mensaje cuando no hay reseñas -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-star-half-alt fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">Aún no tienes reseñas</h4>
            <p class="text-muted mb-4">
                Cuando los alumnos completen tus clases y dejen sus opiniones, aparecerán aquí.
            </p>
            <div class="mb-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Las reseñas ayudan a otros alumnos a conocer la calidad de tus clases
                </small>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
    @endif
</div>

<script>
function filterReviews(rating) {
    const cards = document.querySelectorAll('[data-rating]');
    const buttons = document.querySelectorAll('.btn-group button');
    
    // Actualizar botones
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if ((rating === 'all' && btn.textContent === 'Todas') ||
            (rating === '5' && btn.textContent.includes('5 ⭐')) ||
            (rating === '4' && btn.textContent.includes('4 ⭐')) ||
            (rating === '3' && btn.textContent.includes('3 ⭐'))) {
            btn.classList.add('active');
        }
    });
    
    // Filtrar cards
    cards.forEach(card => {
        const cardRating = parseInt(card.dataset.rating);
        let show = false;
        
        if (rating === 'all') {
            show = true;
        } elseif (rating === '5') {
            show = cardRating === 5;
        } elseif (rating === '4') {
            show = cardRating === 4;
        } elseif (rating === '3') {
            show = cardRating <= 3;
        }
        
        card.parentElement.style.display = show ? 'block' : 'none';
    });
}
</script>
@endsection
