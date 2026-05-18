<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::students()->pluck('id');
        $teachers = User::teachers()->pluck('id');
        $pairs = [];

        foreach ($students as $studentId) {
            $favoriteTeachers = $teachers->random(min(4, $teachers->count()));

            foreach ($favoriteTeachers as $teacherId) {
                $key = $studentId.'-'.$teacherId;
                if (isset($pairs[$key])) {
                    continue;
                }
                $pairs[$key] = true;

                Favorite::query()->create([
                    'student_id' => $studentId,
                    'teacher_id' => $teacherId,
                ]);
            }
        }

        Favorite::query()->firstOrCreate([
            'student_id' => $students->first(),
            'teacher_id' => $teachers->first(),
        ]);
    }
}
