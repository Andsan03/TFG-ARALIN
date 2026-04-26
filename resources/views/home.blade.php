@extends('layouts.app')

@section('content')

{{-- HERO --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1 class="fw-bold display-5 mb-3">
                    Aprende lo que quieras con el
                    <span class="text-primary">profesor ideal</span>
                </h1>
                <p class="text-muted fs-5 mb-4">
                    Aralin conecta alumnos y profesores de forma directa, sin intermediarios.
                    Presencial u online, a tu ritmo.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">
                        Empieza gratis
                    </a>
                    <a href="#como-funciona" class="btn btn-outline-primary btn-lg px-4">
                        ¿Cómo funciona?
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80"
                     class="hero-img" alt="Clases particulares">
            </div>
        </div>
    </div>
</section>

{{-- SERVICIOS --}}
<section class="py-5 bg-white" id="servicios">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Todo lo que necesitas para aprender</h2>
            <p class="text-muted">Una plataforma sencilla para alumnos y profesores.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="fw-bold">Búsqueda con filtros</h4>
                    <p class="text-muted mb-0">Encuentra clases por materia, precio, modalidad y nivel en un solo sitio.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4 class="fw-bold">Inteligencia Artificial</h4>
                    <p class="text-muted mb-0">La IA detecta tu nivel con un cuestionario y te recomienda las clases más adecuadas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="fw-bold">Gestión de reservas</h4>
                    <p class="text-muted mb-0">Panel de control para gestionar reservas, valoraciones y horarios.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-video"></i>
                    </div>
                    <h4 class="fw-bold">Clases online</h4>
                    <p class="text-muted mb-0">Enlace de videollamada generado automáticamente al confirmar la reserva.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="fw-bold">Valoraciones reales</h4>
                    <p class="text-muted mb-0">Solo pueden valorar alumnos que han completado la clase.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card">
                    <div class="icono bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="fw-bold">Administración</h4>
                    <p class="text-muted mb-0">Panel de administrador para moderar usuarios y anuncios.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SOBRE NOSOTROS --}}
<section class="py-5 bg-light" id="nosotros">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80"
                     class="img-seccion" alt="Sobre Aralin">
            </div>
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3">Aralin: donde la lección cobra vida</h2>
                <p class="text-muted">
                    <strong>Aralin</strong> significa <em>lección</em> en tagalog. Nació de una necesidad real:
                    hay personas que quieren aprender pero no encuentran la enseñanza adecuada, y personas
                    con conocimientos que no tienen dónde ofrecerse como profesores.
                </p>
                <p class="text-muted">Aralin elimina esa barrera conectando directamente al alumno con el profesor.</p>
                <ul class="lista-check">
                    <li>
                        <i class="fas fa-check-circle text-success"></i>
                        Aprende lo que quieras al precio que puedas permitirte
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success"></i>
                        Elige horario, modalidad y profesor según tus necesidades
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success"></i>
                        Cualquiera puede convertirse en profesor
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- CÓMO FUNCIONA --}}
<section class="py-5 bg-white" id="como-funciona">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">¿Cómo funciona?</h2>
            <p class="text-muted">En tres pasos puedes empezar a aprender o a enseñar.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="paso">
                    <div class="numero bg-primary text-white">1</div>
                    <h5 class="fw-bold">Créate una cuenta</h5>
                    <p class="text-muted">Regístrate como alumno o profesor en menos de un minuto.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="paso">
                    <div class="numero bg-primary text-white">2</div>
                    <h5 class="fw-bold">Encuentra o publica tu clase</h5>
                    <p class="text-muted">Busca con filtros o publica tu anuncio con precio y horario.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="paso">
                    <div class="numero bg-primary text-white">3</div>
                    <h5 class="fw-bold">¡Empieza a aprender!</h5>
                    <p class="text-muted">Reserva, recibe la confirmación y conéctate. Presencial u online.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CONTACTO --}}
<section class="py-5 bg-light" id="contacto">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-md-5">
                <h2 class="fw-bold">¿Tienes alguna duda?</h2>
                <p class="text-muted mb-4">Escríbenos y te respondemos lo antes posible.</p>
                <p class="text-muted">
                    <i class="fas fa-envelope text-primary me-2"></i> info@aralin-tfg.com
                </p>
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt text-primary me-2"></i> Campus Universitario, Ciudad Real
                </p>
            </div>
            <div class="col-md-7">
                <div class="contacto-card shadow-sm border">
                    <form>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" class="form-control" placeholder="Tu nombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo</label>
                                <input type="email" class="form-control" placeholder="nombre@ejemplo.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mensaje</label>
                                <textarea class="form-control" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Enviar mensaje
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="mi-footer bg-dark text-white text-center">
    <div class="container">
        <p class="mb-1"><strong>aralin</strong> — Plataforma de Clases Particulares</p>
        <p class="mb-0 text-white-50">© {{ date('Y') }} Andrew Sanchez Abello · DAW</p>
    </div>
</footer>

@endsection