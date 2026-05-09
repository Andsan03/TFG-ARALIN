@extends('layouts.app')

@section('title', 'Resultados de Evaluación')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Resultados de tu Evaluación
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Nivel Detectado -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-level-up-alt me-2"></i>
                                        Tu Nivel Detectado
                                    </h5>
                                    <div class="mb-3">
                                        <span class="badge bg-{{ $assessment->detected_level === 'principiante' ? 'success' : ($assessment->detected_level === 'intermedio' ? 'warning' : 'danger') }} fs-4 p-3">
                                            {{ ucfirst($assessment->detected_level) }}
                                        </span>
                                    </div>
                                    
                                    @if($assessment->detected_level === 'principiante')
                                        <p class="text-muted">
                                            <i class="fas fa-seedling me-2"></i>
                                            Estás comenzando tu camino en la programación. ¡Sigue así!
                                        </p>
                                    @elseif($assessment->detected_level === 'intermedio')
                                        <p class="text-muted">
                                            <i class="fas fa-code me-2"></i>
                                            Tienes conocimientos sólidos. Es hora de desafiarte más.
                                        </p>
                                    @else
                                        <p class="text-muted">
                                            <i class="fas fa-rocket me-2"></i>
                                            ¡Nivel avanzado! Estás listo para proyectos complejos.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recomendación de la IA -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title text-info">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        Recomendación Personalizada
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-robot me-2"></i>
                                        <strong>Consejo de IA:</strong> {{ $assessment->ai_recommendation }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-calendar me-2"></i>
                                        Fecha de Evaluación
                                    </h6>
                                    <p class="mb-0">{{ $assessment->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-tag me-2"></i>
                                        Área Evaluada
                                    </h6>
                                    <p class="mb-0">{{ ucfirst($assessment->subject) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('student.search') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Buscar Clases Adecuadas
                                </a>
                                <a href="{{ route('assessment.create', $assessment->subject) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>
                                    Reintentar Evaluación
                                </a>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>
                                    Ir al Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Respuestas Detalladas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-2"></i>
                                        Resultados por Pregunta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($assessment->answers)
                                        <?php
                                        $correctCount = 0;
                                        $incorrectCount = 0;
                                        ?>
                                        @foreach($assessment->answers as $questionId => $userAnswer)
                                            @php
                                            $question = \App\Models\Question::find($questionId);
                                            $isCorrect = $question && $question->correct_option === $userAnswer;
                                            if ($isCorrect) {
                                                $correctCount++;
                                            } else {
                                                $incorrectCount++;
                                            }
                                            @endphp
                                            <div class="mb-3 p-3 border rounded @if($isCorrect) border-success bg-light @else border-danger bg-light @endif">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <strong class="d-block mb-2">Pregunta {{ $loop->index + 1 }}:</strong>
                                                        <p class="mb-1 text-secondary">{{ $question->question_text ?? 'Pregunta no encontrada' }}</p>
                                                        
                                                        <!-- Mostrar las opciones sin indicar cuál es correcta -->
                                                        <div class="small text-muted">
                                                            @if($question->type === 'multiple_choice')
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <strong>Tu respuesta:</strong> 
                                                                        <span class="badge @if($isCorrect) bg-success @else bg-danger @endif text-white">
                                                                            {{ strtoupper($userAnswer) }}) {{ $question->{'option_' . $userAnswer} ?? 'Opción no encontrada' }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Resultado:</strong>
                                                                        @if($isCorrect)
                                                                            <span class="text-success fw-bold">
                                                                                <i class="fas fa-check-circle me-1"></i>Correcto
                                                                            </span>
                                                                        @else
                                                                            <span class="text-danger fw-bold">
                                                                                <i class="fas fa-times-circle me-1"></i>Incorrecto
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <!-- Resumen de resultados -->
                                        <div class="mt-4 p-3 bg-light rounded">
                                            <div class="row text-center">
                                                <div class="col-md-4">
                                                    <div class="p-3">
                                                        <h4 class="text-success mb-1">{{ $correctCount }}</h4>
                                                        <p class="mb-0">Correctas</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3">
                                                        <h4 class="text-danger mb-1">{{ $incorrectCount }}</h4>
                                                        <p class="mb-0">Incorrectas</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3">
                                                        <h4 class="text-primary mb-1">{{ $correctCount + $incorrectCount }}</h4>
                                                        <p class="mb-0">Total</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">No hay respuestas detalladas disponibles.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
