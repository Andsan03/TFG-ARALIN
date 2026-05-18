<?php

namespace Database\Factories;

use App\Models\SearchHistory;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SearchHistory>
 */
class SearchHistoryFactory extends Factory
{
    protected $model = SearchHistory::class;

    public function definition(): array
    {
        $queries = [
            'python', 'matemáticas selectividad', 'inglés B2', 'guitarra',
            'diseño ux', 'laravel', 'piano', 'marketing digital',
        ];

        return [
            'student_id' => User::factory()->student(),
            'query' => fake()->randomElement($queries),
            'category' => fake()->optional(0.8)->randomElement(SeederCatalog::CATEGORIES),
            'modality' => fake()->optional(0.7)->randomElement(SeederCatalog::MODALITIES),
            'max_price' => fake()->optional(0.6)->randomFloat(2, 15, 50),
        ];
    }

    public function forStudent(User|int $student): static
    {
        $studentId = $student instanceof User ? $student->id : $student;

        return $this->state(fn () => ['student_id' => $studentId]);
    }
}
