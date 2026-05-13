@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- CABECERA --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Editar clase</h3>
                <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>

            {{-- ERRORES --}}
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.classes.update', $class->id) }}">
                @csrf
                @method('PUT')

                {{-- INFORMACIÓN BÁSICA --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Información básica</h6>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold small">Título de la clase</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="{{ old('title', $class->title) }}" required
                               placeholder="Ej: Matemáticas para principiantes">
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold small">Descripción</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required
                                  placeholder="Describe en qué consiste tu clase, qué aprenderán los alumnos, materiales necesarios...">{{ old('description', $class->description) }}</textarea>
                        <div class="form-text">Máximo 2000 caracteres. Una buena descripción aumenta las reservas.</div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold small">Categoría</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="matematicas"  {{ old('category', $class->category) == 'matematicas'  ? 'selected' : '' }}>Matemáticas</option>
                                <option value="ciencias"     {{ old('category', $class->category) == 'ciencias'     ? 'selected' : '' }}>Ciencias</option>
                                <option value="idiomas"      {{ old('category', $class->category) == 'idiomas'      ? 'selected' : '' }}>Idiomas</option>
                                <option value="arte"         {{ old('category', $class->category) == 'arte'         ? 'selected' : '' }}>Arte</option>
                                <option value="musica"       {{ old('category', $class->category) == 'musica'       ? 'selected' : '' }}>Música</option>
                                <option value="deporte"      {{ old('category', $class->category) == 'deporte'      ? 'selected' : '' }}>Deporte</option>
                                <option value="programacion" {{ old('category', $class->category) == 'programacion' ? 'selected' : '' }}>Programación</option>
                                <option value="negocios"     {{ old('category', $class->category) == 'negocios'     ? 'selected' : '' }}>Negocios</option>
                                <option value="otros"        {{ old('category', $class->category) == 'otros'        ? 'selected' : '' }}>Otros</option>
                            </select>
                            @error('category')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="level" class="form-label fw-semibold small">Nivel</label>
                            <select id="level" name="level" class="form-select" required>
                                <option value="">Selecciona un nivel</option>
                                <option value="beginner"     {{ old('level', $class->level) == 'beginner'     ? 'selected' : '' }}>Principiante</option>
                                <option value="intermediate" {{ old('level', $class->level) == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                                <option value="advanced"     {{ old('level', $class->level) == 'advanced'     ? 'selected' : '' }}>Avanzado</option>
                                <option value="all"          {{ old('level', $class->level) == 'all'          ? 'selected' : '' }}>Todos los niveles</option>
                            </select>
                            @error('level')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- PRECIO Y MODALIDAD --}}
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-3">Precio y modalidad</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="price_per_hour" class="form-label fw-semibold small">Precio por hora (€)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-euro-sign text-primary"></i>
                                </span>
                                <input type="number" id="price_per_hour" name="price_per_hour"
                                       class="form-control"
                                       value="{{ old('price_per_hour', $class->price_per_hour) }}"
                                       min="1" max="999" step="0.01" required>
                            </div>
                            @error('price_per_hour')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Modalidad</label>
                            <div class="d-flex gap-2">
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="modality"
                                           id="mod-online" value="online"
                                           {{ old('modality', $class->modality->value) == 'online' ? 'checked' : '' }}
                                           onchange="toggleLocation()" required>
                                    <label class="btn btn-outline-primary w-100 btn-sm py-2" for="mod-online">
                                        <i class="fas fa-video d-block mb-1"></i>Online
                                    </label>
                                </div>
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="modality"
                                           id="mod-presencial" value="presencial"
                                           {{ old('modality', $class->modality->value) == 'presencial' ? 'checked' : '' }}
                                           onchange="toggleLocation()">
                                    <label class="btn btn-outline-primary w-100 btn-sm py-2" for="mod-presencial">
                                        <i class="fas fa-map-marker-alt d-block mb-1"></i>Presencial
                                    </label>
                                </div>
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="modality"
                                           id="mod-ambas" value="ambas"
                                           {{ old('modality', $class->modality->value) == 'ambas' ? 'checked' : '' }}
                                           onchange="toggleLocation()">
                                    <label class="btn btn-outline-primary w-100 btn-sm py-2" for="mod-ambas">
                                        <i class="fas fa-globe d-block mb-1"></i>Ambas
                                    </label>
                                </div>
                            </div>
                            @error('modality')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Ubicación (presencial/ambas) --}}
                    <div id="location-field" style="display:none">
                        <label for="location" class="form-label fw-semibold small">Ubicación</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </span>
                            <input type="text" id="location" name="location" class="form-control"
                                   value="{{ old('location', $class->location ?? '') }}"
                                   placeholder="Ej: Calle Gran Vía 123, Madrid">
                        </div>
                        <div class="form-text">Indica la dirección donde se impartirá la clase.</div>
                        @error('location')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- CONSEJOS --}}
                <div class="bg-light border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Consejos para tu clase
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li class="mb-1">Sé específico en el título y la descripción</li>
                        <li class="mb-1">Indica los requisitos previos que necesitan los alumnos</li>
                        <li class="mb-1">Menciona los materiales que se necesitarán</li>
                        <li>Establece un precio competitivo para empezar a conseguir reservas</li>
                    </ul>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fas fa-save me-2"></i>Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function toggleLocation() {
    const modality = document.querySelector('input[name="modality"]:checked')?.value;
    const field = document.getElementById('location-field');
    const input = document.getElementById('location');
    const show = modality === 'presencial' || modality === 'ambas';
    field.style.display = show ? 'block' : 'none';
    show ? input.setAttribute('required', 'required') : input.removeAttribute('required');
}

document.addEventListener('DOMContentLoaded', toggleLocation);
</script>

@endsection