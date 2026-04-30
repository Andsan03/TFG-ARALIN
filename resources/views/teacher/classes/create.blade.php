@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Crear tu Primera Clase
                    </h5>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold">¡Bienvenido Profesor!</h4>
                        <p class="text-muted">Comienza creando tu primera clase para que los alumnos puedan reservarla.</p>
                    </div>

                    <form method="POST" action="{{ route('teacher.classes.store') }}">
                        @csrf

                        <!-- Información Básica -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label fw-semibold">
                                    <i class="fas fa-heading me-1"></i>Título de la Clase
                                </label>
                                <input type="text" id="title" name="title" class="form-control" 
                                       value="{{ old('title') }}" required
                                       placeholder="Ej: Matemáticas para Principiantes">
                                @error('title')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Categoría y Nivel -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1"></i>Categoría
                                </label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="matematicas" {{ old('category') == 'matematicas' ? 'selected' : '' }}>Matemáticas</option>
                                    <option value="ciencias" {{ old('category') == 'ciencias' ? 'selected' : '' }}>Ciencias</option>
                                    <option value="idiomas" {{ old('category') == 'idiomas' ? 'selected' : '' }}>Idiomas</option>
                                    <option value="arte" {{ old('category') == 'arte' ? 'selected' : '' }}>Arte</option>
                                    <option value="musica" {{ old('category') == 'musica' ? 'selected' : '' }}>Música</option>
                                    <option value="deporte" {{ old('category') == 'deporte' ? 'selected' : '' }}>Deporte</option>
                                    <option value="programacion" {{ old('category') == 'programacion' ? 'selected' : '' }}>Programación</option>
                                    <option value="negocios" {{ old('category') == 'negocios' ? 'selected' : '' }}>Negocios</option>
                                    <option value="otros" {{ old('category') == 'otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                                @error('category')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="level" class="form-label fw-semibold">
                                    <i class="fas fa-graduation-cap me-1"></i>Nivel
                                </label>
                                <select id="level" name="level" class="form-select" required>
                                    <option value="">Selecciona un nivel</option>
                                    <option value="principiante" {{ old('level') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                                    <option value="intermedio" {{ old('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                    <option value="avanzado" {{ old('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                    <option value="experto" {{ old('level') == 'experto' ? 'selected' : '' }}>Experto</option>
                                    <option value="todos" {{ old('level') == 'todos' ? 'selected' : '' }}>Todos los niveles</option>
                                </select>
                                @error('level')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left me-1"></i>Descripción
                            </label>
                            <textarea id="description" name="description" class="form-control" rows="4" required
                                      placeholder="Describe en qué consiste tu clase, qué aprenderán los alumnos, materiales necesarios, etc...">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Máximo 2000 caracteres</small>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Precio y Modalidad -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="price_per_hour" class="form-label fw-semibold">
                                    <i class="fas fa-euro-sign me-1"></i>Precio por hora
                                </label>
                                <input type="number" id="price_per_hour" name="price_per_hour" class="form-control" 
                                       value="{{ old('price_per_hour', 20) }}" min="0" max="999.99" step="0.01" required>
                                @error('price_per_hour')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="modality" class="form-label fw-semibold">
                                    <i class="fas fa-laptop me-1"></i>Modalidad
                                </label>
                                <select id="modality" name="modality" class="form-select" required onchange="toggleLocationField()">
                                    <option value="">Selecciona modalidad</option>
                                    <option value="online" {{ old('modality') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="presential" {{ old('modality') == 'presential' ? 'selected' : '' }}>Presencial</option>
                                    <option value="mixta" {{ old('modality') == 'mixta' ? 'selected' : '' }}>Mixta</option>
                                </select>
                                @error('modality')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ubicación (solo para presencial y mixta) -->
                        <div class="mb-4" id="locationField" style="display: none;">
                            <label for="location" class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>Ubicación
                            </label>
                            <input type="text" id="location" name="location" class="form-control" 
                                   value="{{ old('location') }}" placeholder="Ej: Calle Gran Vía 123, Madrid">
                            <small class="form-text text-muted">Indica la dirección donde se impartirá la clase presencial</small>
                            @error('location')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Crear Clase
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fas fa-info-circle me-2"></i>Consejos para tu primera clase
                    </h6>
                    <ul class="mb-0 small">
                        <li>Sé específico en el título y descripción</li>
                        <li>Incluye el nivel y requisitos previos</li>
                        <li>Menciona materiales que necesitarán los alumnos</li>
                        <li>Establece un precio competitivo para empezar</li>
                        <li>Una buena descripción aumenta las reservas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLocationField() {
        const modality = document.getElementById('modality').value;
        const locationField = document.getElementById('locationField');
        
        if (modality === 'presential' || modality === 'mixta') {
            locationField.style.display = 'block';
            document.getElementById('location').setAttribute('required', 'required');
        } else {
            locationField.style.display = 'none';
            document.getElementById('location').removeAttribute('required');
        }
    }

    // Ejecutar la función al cargar la página para mantener el estado correcto
    document.addEventListener('DOMContentLoaded', function() {
        toggleLocationField();
    });
</script>
@endsection
