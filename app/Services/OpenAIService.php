<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use OpenAI;

class OpenAIService
{
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 1000; // milliseconds
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Evaluar respuestas usando OpenAI con retry y cache
     */
    public function evaluateAnswers(array $answers, string $category = 'general'): array
    {
        // Generar cache key único
        $cacheKey = $this->generateCacheKey($answers, $category);
        
        // Verificar cache primero
        if (Cache::has($cacheKey)) {
            Log::channel('assessment')->info('Usando resultado cacheado para evaluación');
            return Cache::get($cacheKey);
        }

        // Intentar con retry
        $result = $this->withRetry(function () use ($answers, $category) {
            return $this->callOpenAI($answers, $category);
        });

        // Cachear resultado exitoso
        if ($result) {
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            Log::channel('assessment')->info('Evaluación cacheada exitosamente');
        }

        return $result;
    }

    /**
     * Llamar a OpenAI con manejo de errores mejorado
     */
    private function callOpenAI(array $answers, string $category): array
    {
        try {
            $prompt = $this->buildPrompt($answers, $category);
            
            $response = OpenAI::client(config('services.openai.api_key'))
                ->chat()
                ->create([
                    'model' => config('services.openai.model', 'gpt-3.5-turbo'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemMessage($category)
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => config('services.openai.max_tokens', 500),
                    'temperature' => config('services.openai.temperature', 0.3)
                ]);

            $aiResponse = $response->choices[0]->message->content;
            
            Log::channel('assessment')->info('Respuesta de OpenAI recibida exitosamente');
            
            return $this->parseAIResponse($aiResponse);

        } catch (\Exception $e) {
            Log::channel('assessment')->error('Error en llamada a OpenAI: ' . $e->getMessage());
            
            // Lanzar excepción específica para mejor manejo
            if (str_contains($e->getMessage(), 'rate limit')) {
                throw new \Exception('RATE_LIMIT', 429);
            } elseif (str_contains($e->getMessage(), 'insufficient quota')) {
                throw new \Exception('INSUFFICIENT_QUOTA', 402);
            } elseif (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'connection')) {
                throw new \Exception('CONNECTION_ERROR', 503);
            } else {
                throw new \Exception('OPENAI_ERROR', 500);
            }
        }
    }

    /**
     * Implementar retry con backoff exponencial
     */
    private function withRetry(callable $callback): array
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $lastException = $e;
                
                // No reintentar para ciertos errores
                if (in_array($e->getMessage(), ['INSUFFICIENT_QUOTA', 'OPENAI_ERROR'])) {
                    throw $e;
                }
                
                // Para rate limit, esperar más tiempo
                $delay = $e->getMessage() === 'RATE_LIMIT' 
                    ? (self::RETRY_DELAY * pow(2, $attempt - 1)) * 2 
                    : self::RETRY_DELAY * pow(2, $attempt - 1);
                
                Log::channel('assessment')->warning("Intento $attempt fallido, reintentando en {$delay}ms");
                
                if ($attempt < self::MAX_RETRIES) {
                    usleep($delay * 1000); // Convertir a microsegundos
                }
            }
        }
        
        throw $lastException;
    }

    /**
     * Generar cache key único
     */
    private function generateCacheKey(array $answers, string $category): string
    {
        return 'assessment_' . md5(json_encode($answers) . '_' . $category);
    }

    /**
     * Construir prompt contextual
     */
    private function buildPrompt(array $answers, string $category): string
    {
        $prompt = "Basado en las siguientes respuestas de un cuestionario de {$category}, determina el nivel del estudiante (principiante, intermedio, avanzado) y proporciona una recomendación personalizada:\n\n";
        
        $questionNumber = 1;
        foreach ($answers as $questionId => $answer) {
            $prompt .= "Pregunta {$questionNumber}: {$answer}\n";
            $questionNumber++;
        }
        
        $prompt .= "\nPor favor, responde en el siguiente formato exacto:\n";
        $prompt .= "NIVEL: [principiante|intermedio|avanzado]\n";
        $prompt .= "RECOMENDACIÓN: [tu recomendación personalizada aquí]\n\n";
        $prompt .= "La recomendación debe ser específica y práctica, sugiriendo qué temas o tecnologías debería estudiar el estudiante según su nivel detectado.";
        
        return $prompt;
    }

    /**
     * Obtener mensaje del sistema según categoría
     */
    private function getSystemMessage(string $category): string
    {
        $messages = [
            'programación' => 'Eres un experto en evaluación de nivel de conocimientos de programación. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.',
            'diseño' => 'Eres un experto en evaluación de nivel de conocimientos de diseño. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.',
            'idiomas' => 'Eres un experto en evaluación de nivel de conocimientos de idiomas. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.',
            'música' => 'Eres un experto en evaluación de nivel de conocimientos de música. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.',
            'marketing' => 'Eres un experto en evaluación de nivel de conocimientos de marketing. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.',
            'general' => 'Eres un experto en evaluación de nivel de conocimientos. Analiza las respuestas del estudiante y determina su nivel (principiante, intermedio, avanzado) y proporciona una recomendación personalizada.'
        ];
        
        return $messages[$category] ?? $messages['general'];
    }

    /**
     * Parsear respuesta de OpenAI
     */
    private function parseAIResponse(string $response): array
    {
        // Extraer nivel
        $level = 'principiante'; // valor por defecto
        if (preg_match('/NIVEL:\s*(principiante|intermedio|avanzado)/i', $response, $matches)) {
            $level = strtolower($matches[1]);
        }

        // Extraer recomendación
        $recommendation = 'Continúa practicando los fundamentos.'; // valor por defecto
        if (preg_match('/RECOMENDACIÓN:\s*(.+)/i', $response, $matches)) {
            $recommendation = trim($matches[1]);
        }

        return [
            'level' => $level,
            'recommendation' => $recommendation
        ];
    }

    /**
     * Evaluar localmente (fallback mejorado para preguntas tipo test)
     */
    public function evaluateLocally(array $answers): array
    {
        Log::channel('assessment')->info('Usando evaluación local como fallback');
        
        $correctAnswers = 0;
        $totalQuestions = count($answers);
        $detailedResults = [];
        
        foreach ($answers as $questionId => $selectedOption) {
            // Obtener la pregunta correcta desde la base de datos
            $question = \App\Models\Question::find($questionId);
            
            $isCorrect = false;
            if ($question && $question->correct_option === $selectedOption) {
                $correctAnswers++;
                $isCorrect = true;
            }
            
            // Guardar información detallada de cada respuesta
            $detailedResults[$questionId] = [
                'question_text' => $question->question_text ?? 'Pregunta no encontrada',
                'user_answer' => $selectedOption,
                'user_answer_text' => $question ? $question->{'option_' . $selectedOption} : 'Opción no encontrada',
                'is_correct' => $isCorrect,
                'correct_option' => $question->correct_option ?? null,
                'correct_answer_text' => $question ? $question->{'option_' . ($question->correct_option ?? '')} : 'Opción no encontrada'
            ];
        }
        
        // Calcular porcentaje de respuestas correctas
        $percentage = ($correctAnswers / $totalQuestions) * 100;
        
        // Determinar nivel basado en el porcentaje
        if ($percentage >= 80) {
            $level = 'avanzado';
            $recommendation = 'Excelente nivel. Has respondido correctamente la mayoría de las preguntas. Demuestras un conocimiento profundo del tema. Recomendamos enfocarte en conceptos avanzados y aplicaciones prácticas.';
        } elseif ($percentage >= 50) {
            $level = 'intermedio';
            $recommendation = 'Buen nivel intermedio. Tienes sólidos fundamentos pero hay espacio para mejorar. Sigue practicando con conceptos más complejos y aplica tus conocimientos en proyectos reales.';
        } else {
            $level = 'principiante';
            $recommendation = 'Estás comenzando tu camino. Es normal estar en este nivel. Enfócate en los fundamentos básicos, practica regularmente y no dudes en buscar recursos adicionales para reforzar tu aprendizaje.';
        }
        
        Log::channel('assessment')->info("Evaluación local completada: {$correctAnswers}/{$totalQuestions} correctas ({$percentage}%) - Nivel: {$level}");
        
        return [
            'level' => $level,
            'recommendation' => $recommendation,
            'score' => $correctAnswers,
            'total' => $totalQuestions,
            'percentage' => round($percentage, 1),
            'detailed_results' => $detailedResults
        ];
    }
}
