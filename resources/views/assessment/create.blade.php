@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- CABECERA --}}
            <div class="text-center mb-4">
                <div class="login-logo mb-3 mx-auto" style="background:#534AB7">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 class="fw-bold mb-1">
                    Evaluación de nivel
                    @if($category) — {{ ucfirst($category) }} @endif
                </h3>
                <p class="text-muted small">
                    Responde las preguntas para que la IA detecte tu nivel y te recomiende las clases más adecuadas.
                </p>
            </div>

            @if($questions->count() > 0)

                <form action="{{ route('assessment.store') }}" method="POST">
                    @csrf
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif

                    {{-- PREGUNTAS --}}
                    @foreach($questions as $index => $question)
                        <div class="bg-white border rounded-3 p-4 mb-3">

                            {{-- Número y enunciado --}}
                            <div class="d-flex gap-3 mb-3">
                                <div class="assessment-num bg-primary bg-opacity-10 text-primary fw-bold rounded-3 flex-shrink-0"
                                     style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:.9rem">
                                    {{ $index + 1 }}
                                </div>
                                <p class="fw-semibold mb-0 pt-1">{{ $question->question_text }}</p>
                            </div>

                            {{-- Opciones --}}
                            @if($question->type === 'multiple_choice' && isset($question->shuffled_options))
                                @foreach($question->shuffled_options as $key => $option)
                                    <div class="assessment-option">
                                        <input class="form-check-input d-none" type="radio"
                                               name="answers[{{ $question->id }}]"
                                               id="ans_{{ $question->id }}_{{ $key }}"
                                               value="{{ $key }}" required>
                                        <label class="assessment-label" for="ans_{{ $question->id }}_{{ $key }}">
                                            <span class="option-letter">{{ strtoupper($key) }}</span>
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach

                            @else
                                {{-- Opciones fijas A, B, C, D --}}
                                @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $option)
                                    <div class="assessment-option">
                                        <input class="form-check-input d-none" type="radio"
                                               name="answers[{{ $question->id }}]"
                                               id="ans_{{ $question->id }}_{{ $key }}"
                                               value="{{ $key }}" required>
                                        <label class="assessment-label" for="ans_{{ $question->id }}_{{ $key }}">
                                            <span class="option-letter">{{ strtoupper($key) }}</span>
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    @endforeach

                    {{-- BOTÓN --}}
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-robot me-2"></i>Evaluar mi nivel con IA
                        </button>
                    </div>
                    <p class="text-muted small text-center mt-2">
                        La evaluación tarda unos segundos mientras la IA analiza tus respuestas.
                    </p>

                </form>

            @else

                <div class="bg-white border rounded-3 p-5 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3 d-block"></i>
                    <h5 class="text-muted">No hay preguntas disponibles</h5>
                    <p class="text-muted small mb-4">
                        Aún no hay preguntas para esta materia. Puedes reservar la clase directamente.
                    </p>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>

            @endif

        </div>
    </div>
</div>

@endsection