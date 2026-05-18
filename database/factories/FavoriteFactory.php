<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Favorite>
 */
class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        return [
            'student_id' => User::factory()->student(),
            'teacher_id' => User::factory()->teacher(),
        ];
    }

    public function forStudent(User|int $student): static
    {
        $studentId = $student instanceof User ? $student->id : $student;

        return $this->state(fn () => ['student_id' => $studentId]);
    }

    public function forTeacher(User|int $teacher): static
    {
        $teacherId = $teacher instanceof User ? $teacher->id : $teacher;

        return $this->state(fn () => ['teacher_id' => $teacherId]);
    }
}
