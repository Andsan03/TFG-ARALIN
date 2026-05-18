<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class MatematicasQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es la derivada de f(x) = x²?',
                'option_a' => '2x',
                'option_b' => 'x',
                'option_c' => 'x²',
                'option_d' => '2',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Qué es el determinante de una matriz 2×2?',
                'option_a' => 'Un número que resume propiedades lineales',
                'option_b' => 'La suma de sus filas',
                'option_c' => 'El número de columnas',
                'option_d' => 'Su inversa',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es la fórmula del área de un círculo?',
                'option_a' => 'πr²',
                'option_b' => '2πr',
                'option_c' => 'πd',
                'option_d' => 'r²',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Qué representa la pendiente en una recta?',
                'option_a' => 'La inclinación de la recta',
                'option_b' => 'El punto de corte con el eje Y',
                'option_c' => 'La longitud de la recta',
                'option_d' => 'El ángulo en radianes siempre',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es la solución de la ecuación 2x + 6 = 0?',
                'option_a' => 'x = -3',
                'option_b' => 'x = 3',
                'option_c' => 'x = -6',
                'option_d' => 'x = 0',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Qué es una progresión aritmética?',
                'option_a' => 'Sucesión con diferencia constante entre términos',
                'option_b' => 'Sucesión con razón multiplicativa',
                'option_c' => 'Una función exponencial',
                'option_d' => 'Una integral definida',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es el teorema de Pitágoras?',
                'option_a' => 'a² + b² = c² en un triángulo rectángulo',
                'option_b' => 'a + b = c',
                'option_c' => 'a² - b² = c²',
                'option_d' => 'a/b = c/d',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Qué es el límite de (sin x)/x cuando x tiende a 0?',
                'option_a' => '1',
                'option_b' => '0',
                'option_c' => '∞',
                'option_d' => 'No existe',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es la probabilidad de sacar cara en una moneda justa?',
                'option_a' => '1/2',
                'option_b' => '1/4',
                'option_c' => '1',
                'option_d' => '0',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'matemáticas',
                'question_text' => '¿Cuál es tu nivel actual en matemáticas?',
                'option_a' => 'Principiante',
                'option_b' => 'Intermedio',
                'option_c' => 'Avanzado',
                'option_d' => 'Experto universitario',
                'correct_option' => 'b',
            ],
        ];

        foreach ($questions as $question) {
            Question::query()->firstOrCreate(
                ['question_text' => $question['question_text']],
                $question
            );
        }
    }
}
