@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Mis reseñas</h3>
            <p class="text-muted mb-0">Lo que dicen tus alumnos sobre tus clases</p>
        </div>
        @if($reviews->count() > 0)
            <div class="btn-group d-none d-md-flex" role="group">
                <button class="btn btn-primary btn-sm active" onclick="filtrar('all', this)">Todas</button>
                <button class="btn btn-outline-primary btn-sm" onclick="filtrar('5', this)">5 ⭐</button>
                <button class="btn btn-outline-primary btn-sm" onclick="filtrar('4', this)">4 ⭐</button>
                <button class="btn btn-outline-primary btn-sm" onclick="filtrar('3', this)">3 ⭐ o menos</button>
            </div>
        @endif
    </div>

    @if($reviews->count() > 0)

        {{-- STATS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="fw-bold fs-4">{{ $reviews->count() }}</div>
                    <div class="text-muted small">Total reseñas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="fw-bold fs-4">{{ number_format($reviews->avg('rating'), 1) }}</div>
                    <div class="text-muted small">Puntuación media</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                    <div class="fw-bold fs-4">{{ $reviews->where('rating', 5)->count() }}</div>
                    <div class="text-muted small">5 estrellas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="dashboard-stat border rounded-3 p-3 bg-white text-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info mx-auto mb-2">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="fw-bold fs-4">{{ $reviews->where('rating', '>=', 4)->count() }}</div>
                    <div class="text-muted small">4+ estrellas</div>
                </div>
            </div>
        </div>

        {{-- RESEÑAS --}}
        <div class="row g-4" id="reviews-list">
            @foreach($reviews as $review)
                <div class="col-md-6 col-lg-4 review-item" data-rating="{{ $review->rating }}">
                    <div class="review-card bg-white border rounded-3 h-100">

                        {{-- Barra de color según rating --}}
                        <div class="booking-status-bar" style="
                            background: {{ $review->rating >= 4 ? '#198754' : ($review->rating == 3 ? '#ffc107' : '#dc3545') }};
                            height: 4px;">
                        </div>

                        <div class="p-4">

                            {{-- Estrellas y fecha --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                    <span class="text-dark fw-bold ms-1 small">{{ $review->rating }}/5</span>
                                </div>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                            </div>

                            {{-- Alumno --}}
                            <div class="d-flex align-items-center mb-3">
                                @if($review->student->profile_photo)
                                    <img src="{{ asset('storage/' . $review->student->profile_photo) }}"
                                         class="rounded-circle me-2" width="38" height="38" alt="Alumno">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2"
                                         style="width:38px;height:38px;font-weight:700">
                                        {{ substr($review->student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold small">{{ $review->student->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem">{{ $review->student->email }}</div>
                                </div>
                            </div>

                            {{-- Clase --}}
                            <div class="fw-bold small text-primary mb-1">{{ $review->booking->class->title }}</div>
                            <div class="text-muted mb-3" style="font-size:.78rem">
                                <i class="fas fa-tag me-1"></i>{{ $review->booking->class->category }}
                                <span class="mx-1">·</span>
                                <i class="fas fa-signal me-1"></i>{{ $review->booking->class->level }}
                            </div>

                            {{-- Comentario --}}
                            @if($review->comment)
                                <div class="bg-light rounded-3 p-3 mb-3">
                                    <p class="small text-muted mb-0 fst-italic">"{{ $review->comment }}"</p>
                                </div>
                            @else
                                <p class="text-muted small fst-italic mb-3">
                                    <i class="fas fa-comment-slash me-1"></i>Sin comentario
                                </p>
                            @endif

                            {{-- Valoración textual --}}
                            <div class="small">
                                @if($review->rating >= 4)
                                    <i class="fas fa-smile text-success me-1"></i>
                                    <span class="text-success">Excelente</span>
                                @elseif($review->rating == 3)
                                    <i class="fas fa-meh text-warning me-1"></i>
                                    <span class="text-warning">Buena</span>
                                @else
                                    <i class="fas fa-frown text-danger me-1"></i>
                                    <span class="text-danger">Necesita mejorar</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>

    @else

        <div class="text-center py-5">
            <i class="fas fa-star-half-alt fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Aún no tienes reseñas</h5>
            <p class="text-muted small mb-4">
                Cuando los alumnos completen tus clases y dejen sus opiniones, aparecerán aquí.
            </p>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al dashboard
            </a>
        </div>

    @endif

</div>

<script>
function filtrar(rating, btn) {
    document.querySelectorAll('.btn-group button').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline-primary');
    });
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-primary', 'active');

    document.querySelectorAll('.review-item').forEach(item => {
        const r = parseInt(item.dataset.rating);
        let mostrar = false;
        if (rating === 'all')      mostrar = true;
        else if (rating === '5')   mostrar = r === 5;
        else if (rating === '4')   mostrar = r === 4;
        else if (rating === '3')   mostrar = r <= 3;
        item.style.display = mostrar ? '' : 'none';
    });
}
</script>

@endsection