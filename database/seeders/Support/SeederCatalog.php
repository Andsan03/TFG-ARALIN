<?php

namespace Database\Seeders\Support;

final class SeederCatalog
{
    public const DEMO_PASSWORD = 'Aralin2026!';

    public const ADMIN_EMAIL = 'admin@aralin.test';

    /** @var list<string> */
    public const CATEGORIES = [
        'programacion',
        'matematicas',
        'arte',
        'idiomas',
        'musica',
        'ciencias',
        'deporte',
        'negocios',
    ];

    /** @var list<string> */
    public const MODALITIES = ['online', 'presencial', 'ambas'];

    /** @var list<string> */
    public const LEVELS = ['beginner', 'intermediate', 'advanced'];

    /** @var list<string> */
    public const BOOKING_STATUSES = ['pendiente', 'aceptada', 'rechazada', 'completada'];

    /** @var list<string> */
    public const ASSESSMENT_LEVELS = ['principiante', 'intermedio', 'avanzado'];

    /** @var list<string> */
    public const QUESTION_SUBJECTS = ['programación', 'diseño', 'idiomas', 'matemáticas', 'música', 'marketing'];

    /** @var list<string> */
    public const TEACHER_NAMES = [
        'María García López',
        'Carlos Ruiz Martín',
        'Ana Fernández Soto',
        'Javier Moreno Prieto',
        'Lucía Domínguez Vega',
        'Pedro Sánchez Gil',
        'Elena Torres Ramos',
        'Miguel Herrera Castro',
        'Sofía Jiménez Ortega',
        'Daniel Navarro Peña',
    ];

    /** @var list<string> */
    public const STUDENT_NAMES = [
        'Laura Méndez', 'Pablo Iglesias', 'Carmen Vidal', 'Hugo Romero', 'Isabel Costa',
        'Álvaro Molina', 'Claudia Paredes', 'Rubén Delgado', 'Marta Suárez', 'Iván Campos',
        'Nuria Blanco', 'Óscar Reyes', 'Patricia León', 'Sergio Mora', 'Raquel Ibáñez',
        'Adrián Guerrero', 'Beatriz Nieto', 'Víctor Cabrera', 'Cristina Ríos', 'Marcos Peña',
        'Alicia Fuentes', 'Jorge Luna', 'Teresa Gil', 'Francisco Arias', 'Eva Montes',
        'Roberto Silva', 'Inés Pacheco', 'Gonzalo Vargas', 'Paula Benítez', 'Andrés Cordero',
    ];

    public static function teacherEmail(int $index): string
    {
        return sprintf('profesor%02d@aralin.test', $index);
    }

    public static function studentEmail(int $index): string
    {
        return sprintf('alumno%02d@aralin.test', $index);
    }

    public static function jitsiMeetingUrl(int $bookingId): string
    {
        return 'https://meet.jit.si/aralin-demo-'.str_pad((string) $bookingId, 6, '0', STR_PAD_LEFT);
    }
}
