<?php

namespace Database\Factories;

use App\Enums\ClassModality;
use App\Models\Classes;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classes>
 */
class ClassesFactory extends Factory
{
    protected $model = Classes::class;

    public function definition(): array
    {
        $modality = fake()->randomElement(SeederCatalog::MODALITIES);
        $category = fake()->randomElement(SeederCatalog::CATEGORIES);

        return [
            'teacher_id' => User::factory()->teacher(),
            'title' => $this->titleFor($category),
            'description' => fake('es_ES')->paragraphs(4, true),
            'category' => $category,
            'modality' => $modality,
            'price_per_hour' => fake()->randomFloat(2, 12, 65),
            'level' => fake()->randomElement(SeederCatalog::LEVELS),
            'is_active' => true,
            'location' => $this->locationFor($modality),
        ];
    }

    public function forTeacher(User|int $teacher): static
    {
        $teacherId = $teacher instanceof User ? $teacher->id : $teacher;

        return $this->state(fn () => ['teacher_id' => $teacherId]);
    }

    public function category(string $category): static
    {
        return $this->state(fn () => [
            'category' => $category,
            'title' => $this->titleFor($category),
        ]);
    }

    public function modality(string $modality): static
    {
        return $this->state(fn () => [
            'modality' => $modality,
            'location' => $this->locationFor($modality),
        ]);
    }

    public function level(string $level): static
    {
        return $this->state(fn () => ['level' => $level]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function online(): static
    {
        return $this->modality(ClassModality::Online->value);
    }

    public function presencial(): static
    {
        return $this->modality(ClassModality::Presencial->value);
    }

    public function ambas(): static
    {
        return $this->modality(ClassModality::Ambas->value);
    }

    private function titleFor(string $category): string
    {
        $titles = [
            'programacion' => ['Laravel desde cero', 'Python para datos', 'JavaScript moderno', 'Git y DevOps básico'],
            'matematicas' => ['Álgebra lineal aplicada', 'Cálculo diferencial', 'Estadística descriptiva', 'Matemáticas para selectividad'],
            'arte' => ['Diseño UX/UI', 'Branding y identidad visual', 'Figma para principiantes', 'Ilustración digital'],
            'idiomas' => ['Inglés conversacional B1', 'Francés para viajes', 'Preparación Cambridge', 'Español para extranjeros'],
            'musica' => ['Guitarra acústica', 'Teoría musical', 'Piano clásico', 'Producción musical básica'],
            'ciencias' => ['Física para bachillerato', 'Química orgánica', 'Biología celular', 'Robótica educativa'],
            'deporte' => ['Entrenamiento funcional', 'Yoga para principiantes', 'Preparación física', 'Nutrición deportiva'],
            'negocios' => ['Marketing digital', 'Finanzas personales', 'Emprendimiento', 'Excel para negocios'],
        ];

        return fake()->randomElement($titles[$category] ?? ['Clase personalizada de '.$category]);
    }

    private function locationFor(string $modality): ?string
    {
        if ($modality === ClassModality::Online->value) {
            return null;
        }

        $cities = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Bilbao', 'Zaragoza'];

        return fake()->randomElement($cities).', '.fake()->streetAddress();
    }
}
