@extends('layouts.app')

@section('content')
<div class="bg-primary text-white py-5 shadow-sm" style="background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(0, 61, 153, 0.9)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1351&q=80'); background-size: cover; background-position: center;">
    <div class="container py-5 text-center">
        <h1 class="display-3 fw-bold mb-3">Aprende a tu ritmo con Aralin</h1>
        <p class="lead mb-4 fs-4">La plataforma que conecta expertos y alumnos para compartir conocimientos de forma flexible y sin intermediarios.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 gap-3 fw-bold">Empieza ahora</a>
            <a href="#quienes-somos" class="btn btn-outline-light btn-lg px-4">Saber más</a>
        </div>
    </div>
</div>

<div class="py-5" id="que-hacemos">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nuestros Servicios</h2>
            <hr class="mx-auto bg-primary" style="width: 50px; height: 3px;">
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-search-location fa-3x text-primary mb-3"></i>
                    <h4>Búsqueda Centralizada</h4>
                    <p class="text-muted">Encuentra clases por materia, modalidad o precio en un solo sitio, sin procesos lentos.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-brain fa-3x text-primary mb-3"></i>
                    <h4>Inteligencia Artificial</h4>
                    <p class="text-muted">Nuestro algoritmo detecta tu nivel previo y te ofrece recomendaciones personalizadas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h4>Gestión Total</h4>
                    <p class="text-muted">Panel de control completo para organizar tus reservas, valoraciones y horarios.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-light" id="quienes-somos">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded shadow" alt="Sobre nosotros">
            </div>
            <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0">
                <h2 class="fw-bold mb-4">Aralin: Donde la lección cobra vida</h2>
                <p class="fs-5">"Aralin" significa <strong>lección</strong> en tagalog. Nacimos de una necesidad real: conectar a personas que quieren aprender con expertos que desean enseñar de forma accesible.</p>
                <p class="text-muted">Creemos en la flexibilidad y en eliminar intermediarios para que el conocimiento fluya directamente del profesor al alumno.</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i> Accesibilidad para todos los bolsillos.</li>
                    <li><i class="fas fa-check text-success me-2"></i> Oportunidad para nuevos talentos.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="py-5" id="contacto">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2 class="fw-bold">Hablemos</h2>
                <p class="text-muted">¿Tienes dudas sobre cómo convertirte en profesor o cómo funcionan las reservas?</p>
                <div class="d-flex mb-3">
                    <i class="fas fa-envelope text-primary me-3 pt-1"></i>
                    <p>info@aralin-tfg.com</p>
                </div>
                <div class="d-flex">
                    <i class="fas fa-map-marker-alt text-primary me-3 pt-1"></i>
                    <p>Campus Universitario, Ciudad Real</p>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card border-0 shadow-sm p-4">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="Tu nombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" placeholder="nombre@ejemplo.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mensaje</label>
                                <textarea class="form-control" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5">Enviar Mensaje</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 Aralin - Plataforma de Clases Particulares | Andrew Sanchez Abello</p>
    </div>
</footer>
@endsection