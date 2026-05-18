<?php

namespace Database\Factories;

use App\Models\Question;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'subject' => fake()->randomElement(SeederCatalog::QUESTION_SUBJECTS),
            'question_text' => fake('es_ES')->sentence().'?',
            'option_a' => fake('es_ES')->words(3, true),
            'option_b' => fake('es_ES')->words(3, true),
            'option_c' => fake('es_ES')->words(3, true),
            'option_d' => fake('es_ES')->words(3, true),
            'correct_option' => fake()->randomElement(['a', 'b', 'c', 'd']),
        ];
    }

    public function subject(string $subject): static
    {
        return $this->state(fn () => ['subject' => $subject]);
    }
}
