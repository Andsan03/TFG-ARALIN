-- Tabla de usuarios (profesores, alumnos y administradores)
CREATE TABLE users (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    role          ENUM('teacher', 'student', 'admin') NOT NULL DEFAULT 'student',
    profile_photo VARCHAR(255),
    bio           TEXT,
    is_blocked    BOOLEAN NOT NULL DEFAULT FALSE,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
 
-- Tabla de clases publicadas por los profesores
CREATE TABLE classes (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    teacher_id     INT NOT NULL,
    title          VARCHAR(150) NOT NULL,
    description    TEXT NOT NULL,
    category       VARCHAR(100) NOT NULL,
    modality       ENUM('presencial', 'online', 'ambas') NOT NULL DEFAULT 'ambas',
    price_per_hour DECIMAL(8,2) NOT NULL,
    level          ENUM('beginner', 'intermediate', 'advanced', 'all') NOT NULL DEFAULT 'all',
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
 
-- Preguntas del test de nivel (fijas, las pone el admin)
CREATE TABLE questions (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    subject       VARCHAR(100) NOT NULL,
    question_text TEXT NOT NULL,
    option_a      VARCHAR(255) NOT NULL,
    option_b      VARCHAR(255) NOT NULL,
    option_c      VARCHAR(255) NOT NULL,
    option_d      VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
-- Resultado del test de nivel analizado por la IA
CREATE TABLE assessments (
    id                INT PRIMARY KEY AUTO_INCREMENT,
    student_id        INT NOT NULL,
    subject           VARCHAR(100) NOT NULL,
    detected_level    ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    answers           JSON NOT NULL,
    ai_recommendation TEXT NOT NULL,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_student_subject (student_id, subject)
);
 
-- Reservas de clases
CREATE TABLE bookings (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    class_id      INT NOT NULL,
    student_id    INT NOT NULL,
    assessment_id INT DEFAULT NULL,
    status        ENUM('pendiente', 'aceptada', 'rechazada', 'completada') NOT NULL DEFAULT 'pendiente',
    scheduled_at  DATETIME NOT NULL,
    meeting_url   VARCHAR(500),
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id)      REFERENCES classes(id)     ON DELETE CASCADE,
    FOREIGN KEY (student_id)    REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE SET NULL
);
 
-- Valoraciones de los alumnos a los profesores
CREATE TABLE reviews (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL UNIQUE,
    student_id INT NOT NULL,
    teacher_id INT NOT NULL,
    rating     TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id)    ON DELETE CASCADE
);
 
-- Profesores favoritos del alumno
CREATE TABLE favorites (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    teacher_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_favorite (student_id, teacher_id)
);
 
-- Historial de búsquedas del alumno (para las recomendaciones de la IA)
CREATE TABLE search_history (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    query      VARCHAR(255),
    category   VARCHAR(100),
    modality   ENUM('presencial', 'online', 'ambas'),
    max_price  DECIMAL(8,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);