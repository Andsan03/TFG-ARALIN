<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition(): array
    {
        $subject = fake()->randomElement(SeederCatalog::QUESTION_SUBJECTS);
        $level = fake()->randomElement(SeederCatalog::ASSESSMENT_LEVELS);

        return [
            'student_id' => User::factory()->student(),
            'subject' => $subject,
            'detected_level' => $level,
            'answers' => $this->fakeAnswers(),
            'ai_recommendation' => $this->fakeRecommendation($subject, $level),
        ];
    }

    public function forStudent(User|int $student): static
    {
        $studentId = $student instanceof User ? $student->id : $student;

        return $this->state(fn () => ['student_id' => $studentId]);
    }

    public function subject(string $subject): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => $subject,
            'ai_recommendation' => $this->fakeRecommendation(
                $subject,
                $attributes['detected_level'] ?? 'intermedio'
            ),
        ]);
    }

    public function level(string $level): static
    {
        return $this->state(fn (array $attributes) => [
            'detected_level' => $level,
            'ai_recommendation' => $this->fakeRecommendation(
                $attributes['subject'] ?? 'programación',
                $level
            ),
        ]);
    }

    private function fakeAnswers(): array
    {
        $answers = [];
        for ($i = 1; $i <= 10; $i++) {
            $answers[(string) $i] = fake()->randomElement(['a', 'b', 'c', 'd']);
        }

        return $answers;
    }

    private function fakeRecommendation(string $subject, string $level): string
    {
        return match ($level) {
            'principiante' => "Tu nivel en {$subject} es principiante. Te recomendamos clases con nivel beginner y ritmo pausado.",
            'avanzado' => "Dominas bien {$subject}. Te sugerimos clases advanced y proyectos prácticos exigentes.",
            default => "Tienes una base sólida en {$subject}. Las clases intermediate te ayudarán a consolidar conceptos.",
        };
    }
}
