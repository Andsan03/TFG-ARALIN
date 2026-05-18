<?php

namespace Database\Seeders;

use App\Enums\ClassModality;
use App\Models\Classes;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /** @var array<string, list<array{title: string, description: string, price: float}>> */
    private array $templates = [
        'programacion' => [
            ['title' => 'Laravel 11: APIs REST desde cero', 'description' => 'Aprende rutas, controladores, Eloquent y autenticación con proyectos reales.', 'price' => 28.00],
            ['title' => 'Python para análisis de datos', 'description' => 'Pandas, visualización y limpieza de datasets con ejercicios guiados.', 'price' => 32.50],
            ['title' => 'JavaScript moderno (ES6+)', 'description' => 'DOM, fetch, módulos y buenas prácticas para frontend.', 'price' => 25.00],
            ['title' => 'Git, GitHub y trabajo en equipo', 'description' => 'Flujos de ramas, pull requests y resolución de conflictos.', 'price' => 22.00],
        ],
        'matematicas' => [
            ['title' => 'Álgebra para bachillerato', 'description' => 'Ecuaciones, sistemas y factorización con ejemplos tipo examen.', 'price' => 24.00],
            ['title' => 'Cálculo diferencial aplicado', 'description' => 'Límites, derivadas y optimización con problemas contextualizados.', 'price' => 35.00],
            ['title' => 'Estadística y probabilidad', 'description' => 'Distribuciones, inferencia y uso de calculadora/Excel.', 'price' => 30.00],
            ['title' => 'Matemáticas discretas', 'description' => 'Lógica, grafos y combinatoria para ingeniería.', 'price' => 38.00],
        ],
        'arte' => [
            ['title' => 'Diseño UX/UI con Figma', 'description' => 'Wireframes, prototipos y sistemas de diseño accesibles.', 'price' => 29.00],
            ['title' => 'Branding e identidad visual', 'description' => 'Logo, paleta cromática y manual de marca para proyectos reales.', 'price' => 34.00],
            ['title' => 'Ilustración digital con Procreate', 'description' => 'Técnicas de sombreado, color y composición para principiantes.', 'price' => 26.00],
        ],
        'idiomas' => [
            ['title' => 'Inglés conversacional B1-B2', 'description' => 'Speaking, listening y vocabulario útil para entrevistas.', 'price' => 22.00],
            ['title' => 'Francés para viajeros', 'description' => 'Frases cotidianas, pronunciación y cultura francesa.', 'price' => 24.00],
            ['title' => 'Preparación Cambridge B2 First', 'description' => 'Reading, writing, listening y speaking con simulacros.', 'price' => 36.00],
        ],
        'musica' => [
            ['title' => 'Guitarra acústica para principiantes', 'description' => 'Acordes, ritmo y canciones populares desde la primera sesión.', 'price' => 20.00],
            ['title' => 'Teoría musical y solfeo', 'description' => 'Lectura rítmica, escalas y armonía básica.', 'price' => 23.00],
            ['title' => 'Piano clásico nivel intermedio', 'description' => 'Técnica, interpretación y repertorio graduado.', 'price' => 40.00],
        ],
        'ciencias' => [
            ['title' => 'Física para selectividad', 'description' => 'Mecánica, electromagnetismo y resolución de problemas.', 'price' => 33.00],
            ['title' => 'Química orgánica universitaria', 'description' => 'Nomenclatura, reacciones y esquemas de síntesis.', 'price' => 42.00],
        ],
        'deporte' => [
            ['title' => 'Entrenamiento funcional en casa', 'description' => 'Planificación semanal, técnica y prevención de lesiones.', 'price' => 18.00],
            ['title' => 'Yoga y movilidad', 'description' => 'Secuencias para flexibilidad, respiración y descanso.', 'price' => 19.00],
        ],
        'negocios' => [
            ['title' => 'Marketing digital para pymes', 'description' => 'SEO, redes sociales y embudos de conversión.', 'price' => 31.00],
            ['title' => 'Finanzas personales y ahorro', 'description' => 'Presupuesto, inversión básica y gestión de deuda.', 'price' => 27.00],
        ],
    ];

    public function run(): void
    {
        $teachers = User::teachers()->orderBy('id')->get();
        $modalities = SeederCatalog::MODALITIES;
        $levels = SeederCatalog::LEVELS;

        foreach ($teachers as $teacherIndex => $teacher) {
            $categoryOffset = $teacherIndex % count(SeederCatalog::CATEGORIES);

            foreach (SeederCatalog::CATEGORIES as $catIndex => $category) {
                $templates = $this->templates[$category] ?? [
                    ['title' => 'Clase de '.$category, 'description' => 'Sesión personalizada adaptada a tus objetivos.', 'price' => 25.00],
                ];

                $template = $templates[($catIndex + $categoryOffset) % count($templates)];
                $modality = $modalities[($teacherIndex + $catIndex) % count($modalities)];
                $level = $levels[($teacherIndex + $catIndex) % count($levels)];
                $isActive = ! ($teacherIndex === 9 && $catIndex === 0);

                Classes::query()->create([
                    'teacher_id' => $teacher->id,
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'category' => $category,
                    'modality' => $modality,
                    'price_per_hour' => $template['price'] + ($teacherIndex * 0.5),
                    'level' => $level,
                    'is_active' => $isActive,
                    'location' => $this->locationFor($modality),
                ]);
            }

            Classes::query()->create([
                'teacher_id' => $teacher->id,
                'title' => 'Taller intensivo '.$teacher->name,
                'description' => 'Clase extra para probar paginación y filtros con contenido único del profesor.',
                'category' => 'programacion',
                'modality' => ClassModality::Ambas->value,
                'price_per_hour' => 45.00,
                'level' => 'advanced',
                'is_active' => true,
                'location' => 'Madrid, Calle Mayor 15',
            ]);
        }
    }

    private function locationFor(string $modality): ?string
    {
        if ($modality === ClassModality::Online->value) {
            return null;
        }

        return fake('es_ES')->randomElement([
            'Madrid, Calle Gran Vía 28',
            'Barcelona, Passeig de Gràcia 55',
            'Valencia, Calle Colón 12',
            'Sevilla, Avenida de la Constitución 8',
        ]);
    }
}
