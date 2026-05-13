@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            @php
                $levelUi = \App\Enums\AssessmentSkillLevel::presentation($assessment->detected_level);
            @endphp

            {{-- NIVEL DETECTADO --}}
            <div class="bg-white border rounded-3 p-4 mb-4 text-center">

                <div class="login-logo mb-3 mx-auto" style="background: {{ $levelUi['color'] }}">
                    <i class="{{ $levelUi['icon'] }}"></i>
                </div>

                <p class="text-muted small mb-1">Tu nivel detectado en <strong>{{ ucfirst($assessment->subject) }}</strong></p>
                <h2 class="fw-bold mb-2">{{ $levelUi['text'] }}</h2>
                <p class="text-muted small mb-0">{{ $levelUi['message'] }}</p>
            </div>

            {{-- RECOMENDACIÓN IA --}}
            <div class="bg-white border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Recomendación de la IA</h6>
                </div>
                <p class="text-muted mb-0" style="line-height:1.7">{{ $assessment->ai_recommendation }}</p>
            </div>

            {{-- RESUMEN DE RESPUESTAS --}}
            @if($assessment->answers)
                @php
                    $correctCount = 0;
                    $total = count($assessment->answers);
                @endphp

                {{-- Contar correctas --}}
                @foreach($assessment->answers as $questionId => $userAnswer)
                    @php
                        $q = \App\Models\Question::find($questionId);
                        if ($q && $q->correct_option === $userAnswer) $correctCount++;
                    @endphp
                @endforeach

                {{-- Stats de resultado --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Resultados por pregunta</h6>

                    {{-- Mini stats --}}
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="fw-bold fs-4 text-success">{{ $correctCount }}</div>
                                <div class="text-muted small">Correctas</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="fw-bold fs-4 text-danger">{{ $total - $correctCount }}</div>
                                <div class="text-muted small">Incorrectas</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-stat border rounded-3 p-3 text-center">
                                <div class="fw-bold fs-4 text-primary">{{ $total }}</div>
                                <div class="text-muted small">Total</div>
                            </div>
                        </div>
                    </div>

                    {{-- Detalle por pregunta --}}
                    @foreach($assessment->answers as $questionId => $userAnswer)
                        @php
                            $q = \App\Models\Question::find($questionId);
                            $isCorrect = $q && $q->correct_option === $userAnswer;
                        @endphp
                        <div class="d-flex gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class=" mt-1">
                                @if($isCorrect)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger"></i>
                                @endif
                            </div>
                            <div>
                                <div class="small fw-semibold mb-1">
                                    Pregunta {{ $loop->index + 1 }}
                                    @if($q): {{ Str::limit($q->question_text, 60) }} @endif
                                </div>
                                <div class="small text-muted">
                                    Tu respuesta:
                                    <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }}">
                                        {{ strtoupper($userAnswer) }}
                                        @if($q) {{ $q->{'option_' . $userAnswer} ?? '' }} @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @endif

            {{-- INFO --}}
            <div class="bg-white border rounded-3 p-3 mb-4">
                <div class="row g-3 text-center small">
                    <div class="col-6 border-end">
                        <div class="text-muted mb-1">Materia evaluada</div>
                        <div class="fw-semibold">{{ ucfirst($assessment->subject) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1">Fecha</div>
                        <div class="fw-semibold">{{ $assessment->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="d-grid gap-2">
                <a href="{{ route('student.search', ['category' => $assessment->subject]) }}"
                   class="btn btn-primary fw-bold">
                    <i class="fas fa-search me-2"></i>Buscar clases de {{ ucfirst($assessment->subject) }}
                </a>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('assessment.create', $assessment->subject) }}"
                           class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo me-2"></i>Repetir evaluación
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('student.dashboard') }}"
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection