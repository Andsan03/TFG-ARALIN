@extends('layouts.app')

@section('title', 'Evaluación de Nivel')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-brain me-2"></i>
                        Evaluación de Nivel de {{ $category ? ucfirst($category) : 'General' }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        @if($category)
                            Responde las siguientes preguntas para que nuestra IA pueda determinar tu nivel actual en <strong>{{ ucfirst($category) }}</strong> y darte recomendaciones personalizadas.
                        @else
                            Responde las siguientes preguntas para que nuestra IA pueda determinar tu nivel actual y darte recomendaciones personalizadas.
                        @endif
                    </div>

                    <form action="{{ route('assessment.store') }}" method="POST">
                        @csrf
                        @if($category)
                            <input type="hidden" name="category" value="{{ $category }}">
                        @endif
                        
                        @if($questions->count() > 0)
                            @foreach($questions as $index => $question)
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                        {{ $question->question_text }}
                                    </label>
                                    
                                    @if($question->type === 'multiple_choice')
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   id="answer_{{ $question->id }}_a" 
                                                   value="{{ $question->option_a }}" required>
                                            <label class="form-check-label" for="answer_{{ $question->id }}_a">
                                                {{ $question->option_a }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   id="answer_{{ $question->id }}_b" 
                                                   value="{{ $question->option_b }}" required>
                                            <label class="form-check-label" for="answer_{{ $question->id }}_b">
                                                {{ $question->option_b }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   id="answer_{{ $question->id }}_c" 
                                                   value="{{ $question->option_c }}" required>
                                            <label class="form-check-label" for="answer_{{ $question->id }}_c">
                                                {{ $question->option_c }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   id="answer_{{ $question->id }}_d" 
                                                   value="{{ $question->option_d }}" required>
                                            <label class="form-check-label" for="answer_{{ $question->id }}_d">
                                                {{ $question->option_d }}
                                            </label>
                                        </div>
                                    
                                    @elseif($question->type === 'text')
                                        <textarea class="form-control" 
                                                  name="answers[{{ $question->id }}]" 
                                                  rows="3" 
                                                  placeholder="Escribe tu respuesta aquí..."
                                                  required></textarea>
                                    
                                    @else
                                        <input type="text" 
                                               class="form-control" 
                                               name="answers[{{ $question->id }}]" 
                                               placeholder="Tu respuesta..."
                                               required>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No hay preguntas disponibles en este momento. Por favor, intenta más tarde.
                            </div>
                        @endif

                        @if($questions->count() > 0)
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-robot me-2"></i>
                                    Evaluar mi Nivel
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
