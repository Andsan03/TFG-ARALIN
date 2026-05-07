<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar preguntas existentes
        Question::query()->delete();

        $questions = [
            // Preguntas de Programación
            [
                'subject' => 'programación',
                'question_text' => '¿Qué es una variable en programación?',
                'type' => 'multiple_choice',
                'option_a' => 'Un espacio de memoria para almacenar datos',
                'option_b' => 'Un tipo de dato específico',
                'option_c' => 'Una función predefinida',
                'option_d' => 'Un bucle infinito',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'programación',
                'question_text' => '¿Cuál es tu nivel de experiencia con frameworks como Laravel, React o Angular?',
                'type' => 'multiple_choice',
                'option_a' => 'Nunca he usado frameworks',
                'option_b' => 'He usado algunos frameworks básicamente',
                'option_c' => 'Tengo experiencia sólida con varios frameworks',
                'option_d' => 'Soy experto en desarrollo con frameworks',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'programación',
                'question_text' => 'Describe tu experiencia más reciente programando',
                'type' => 'text',
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_option' => '',
            ],

            // Preguntas de Diseño
            [
                'subject' => 'diseño',
                'question_text' => '¿Qué es la teoría del color en diseño gráfico?',
                'type' => 'multiple_choice',
                'option_a' => 'Un conjunto de reglas para mezclar colores',
                'option_b' => 'Un software de diseño',
                'option_c' => 'Una técnica de impresión',
                'option_d' => 'Un estilo artístico',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'diseño',
                'question_text' => '¿Cuál es tu experiencia con Adobe Creative Suite?',
                'type' => 'multiple_choice',
                'option_a' => 'Nunca he usado estos programas',
                'option_b' => 'Conozco lo básico de Photoshop/Illustrator',
                'option_c' => 'Tengo experiencia intermedia con varios programas',
                'option_d' => 'Soy experto en todo el suite de Adobe',
                'correct_option' => 'b',
            ],
            [
                'subject' => 'diseño',
                'question_text' => '¿Qué tipo de proyectos de diseño te interesan más?',
                'type' => 'text',
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_option' => '',
            ],

            // Preguntas de Idiomas
            [
                'subject' => 'idiomas',
                'question_text' => '¿Cuál es tu nivel actual de inglés?',
                'type' => 'multiple_choice',
                'option_a' => 'Principiante (A1-A2)',
                'option_b' => 'Intermedio (B1-B2)',
                'option_c' => 'Avanzado (C1-C2)',
                'option_d' => 'Nativo',
                'correct_option' => 'b',
            ],
            [
                'subject' => 'idiomas',
                'question_text' => '¿Prefieres clases de gramática o conversación?',
                'type' => 'multiple_choice',
                'option_a' => 'Solo gramática',
                'option_b' => 'Solo conversación',
                'option_c' => 'Mix de ambas',
                'option_d' => 'Depende del profesor',
                'correct_option' => 'c',
            ],
            [
                'subject' => 'idiomas',
                'question_text' => '¿Cuál es tu objetivo principal al aprender este idioma?',
                'type' => 'text',
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_option' => '',
            ],

            // Preguntas de Música
            [
                'subject' => 'música',
                'question_text' => '¿Sabes leer partituras musicales?',
                'type' => 'multiple_choice',
                'option_a' => 'No, no sé leer partituras',
                'option_b' => 'Sí, a nivel básico',
                'option_c' => 'Sí, a nivel intermedio',
                'option_d' => 'Sí, soy experto leyendo partituras',
                'correct_option' => 'b',
            ],
            [
                'subject' => 'música',
                'question_text' => '¿Qué instrumento te gustaría aprender?',
                'type' => 'multiple_choice',
                'option_a' => 'Guitarra',
                'option_b' => 'Piano',
                'option_c' => 'Batería',
                'option_d' => 'Violín',
                'correct_option' => 'b',
            ],
            [
                'subject' => 'música',
                'question_text' => '¿Qué estilo musical te interesa más?',
                'type' => 'text',
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_option' => '',
            ],

            // Preguntas de Marketing
            [
                'subject' => 'marketing',
                'question_text' => '¿Qué es el SEO en marketing digital?',
                'type' => 'multiple_choice',
                'option_a' => 'Search Engine Optimization',
                'option_b' => 'Social Media Optimization',
                'option_c' => 'Sales Enhancement Operations',
                'option_d' => 'Strategic Email Outreach',
                'correct_option' => 'a',
            ],
            [
                'subject' => 'marketing',
                'question_text' => '¿Cuál es tu experiencia con redes sociales para negocios?',
                'type' => 'multiple_choice',
                'option_a' => 'Sin experiencia',
                'option_b' => 'Uso personal básico',
                'option_c' => 'He gestionado cuentas de negocios',
                'option_d' => 'Soy experto en marketing digital',
                'correct_option' => 'c',
            ],
            [
                'subject' => 'marketing',
                'question_text' => '¿Qué tipo de marketing te gustaría aprender?',
                'type' => 'text',
                'option_a' => '',
                'option_b' => '',
                'option_c' => '',
                'option_d' => '',
                'correct_option' => '',
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
