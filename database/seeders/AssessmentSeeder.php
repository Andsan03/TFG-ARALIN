<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::students()->orderBy('id')->take(15)->get();

        $scenarios = [
            ['subject' => 'programación', 'detected_level' => 'principiante', 'answers' => ['1' => 'a', '2' => 'b', '3' => 'a', '4' => 'c', '5' => 'a', '6' => 'b', '7' => 'a', '8' => 'd', '9' => 'a', '10' => 'b']],
            ['subject' => 'diseño', 'detected_level' => 'intermedio', 'answers' => ['1' => 'b', '2' => 'b', '3' => 'c', '4' => 'a', '5' => 'b', '6' => 'a', '7' => 'b', '8' => 'c', '9' => 'a', '10' => 'b']],
            ['subject' => 'idiomas', 'detected_level' => 'avanzado', 'answers' => ['1' => 'b', '2' => 'b', '3' => 'a', '4' => 'c', '5' => 'a', '6' => 'b', '7' => 'a', '8' => 'b', '9' => 'c', '10' => 'a']],
            ['subject' => 'matemáticas', 'detected_level' => 'principiante', 'answers' => ['1' => 'a', '2' => 'c', '3' => 'b', '4' => 'a', '5' => 'd', '6' => 'b', '7' => 'c', '8' => 'a', '9' => 'b', '10' => 'c']],
            ['subject' => 'música', 'detected_level' => 'intermedio', 'answers' => ['1' => 'c', '2' => 'b', '3' => 'c', '4' => 'a', '5' => 'b', '6' => 'c', '7' => 'a', '8' => 'b', '9' => 'c', '10' => 'b']],
        ];

        foreach ($students as $index => $student) {
            $scenario = $scenarios[$index % count($scenarios)];

            Assessment::query()->create([
                'student_id' => $student->id,
                'subject' => $scenario['subject'],
                'detected_level' => $scenario['detected_level'],
                'answers' => $scenario['answers'],
                'ai_recommendation' => $this->recommendation($scenario['subject'], $scenario['detected_level']),
            ]);

            if ($index === 0) {
                foreach (SeederCatalog::ASSESSMENT_LEVELS as $level) {
                    Assessment::query()->create([
                        'student_id' => $student->id,
                        'subject' => 'programación',
                        'detected_level' => $level,
                        'answers' => $scenario['answers'],
                        'ai_recommendation' => $this->recommendation('programación', $level),
                    ]);
                }
            }
        }
    }

    private function recommendation(string $subject, string $level): string
    {
        return match ($level) {
            'principiante' => "Hemos detectado nivel principiante en {$subject}. Recomendamos clases beginner con refuerzo de fundamentos y ejercicios guiados paso a paso.",
            'avanzado' => "Tu perfil en {$subject} es avanzado. Te sugerimos clases advanced, retos reales y proyectos que profundicen en casos complejos.",
            default => "Tu nivel en {$subject} es intermedio. Las clases intermediate te permitirán consolidar conceptos y avanzar con proyectos prácticos.",
        };
    }
}
