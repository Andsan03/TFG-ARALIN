<?php

namespace Database\Seeders;

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class SearchHistorySeeder extends Seeder
{
    public function run(): void
    {
        $presets = [
            ['query' => 'laravel', 'category' => 'programacion', 'modality' => 'online', 'max_price' => 35.00],
            ['query' => 'python datos', 'category' => 'programacion', 'modality' => 'online', 'max_price' => 40.00],
            ['query' => 'álgebra', 'category' => 'matematicas', 'modality' => null, 'max_price' => 30.00],
            ['query' => 'inglés B2', 'category' => 'idiomas', 'modality' => 'ambas', 'max_price' => 28.00],
            ['query' => 'diseño figma', 'category' => 'arte', 'modality' => 'online', 'max_price' => 32.00],
            ['query' => 'guitarra', 'category' => 'musica', 'modality' => 'presencial', 'max_price' => 25.00],
            ['query' => 'marketing', 'category' => 'negocios', 'modality' => 'online', 'max_price' => null],
            ['query' => 'física selectividad', 'category' => 'ciencias', 'modality' => null, 'max_price' => 45.00],
        ];

        $students = User::students()->get();

        foreach ($students as $studentIndex => $student) {
            foreach ($presets as $presetIndex => $preset) {
                if (($studentIndex + $presetIndex) % 3 !== 0) {
                    continue;
                }

                SearchHistory::query()->create([
                    'student_id' => $student->id,
                    'query' => $preset['query'],
                    'category' => $preset['category'],
                    'modality' => $preset['modality'],
                    'max_price' => $preset['max_price'],
                ]);
            }
        }

        SearchHistory::query()->create([
            'student_id' => $students->first()->id,
            'query' => 'clases baratas programación',
            'category' => 'programacion',
            'modality' => 'online',
            'max_price' => 20.00,
        ]);
    }
}
