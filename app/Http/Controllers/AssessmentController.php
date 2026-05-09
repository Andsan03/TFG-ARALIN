<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    public function __construct(private OpenAIService $openAIService)
    {
    }

    /**
     * Mostrar formulario de evaluación
     */
    public function create($category = null)
    {
        // Filtrar preguntas por categoría si se proporciona
        $questions = \App\Models\Question::when($category, function ($query, $category) {
            return $query->where('subject', $category);
        })->orderBy('id')->get();
        
        // Seleccionar solo 10 preguntas aleatoriamente de las disponibles
        $questions = $questions->random(10);
        
        // Mezclar las 10 preguntas seleccionadas
        $questions = $questions->shuffle();
        
        // Preparar opciones mezcladas para cada pregunta
        $questionsWithShuffledOptions = $questions->map(function ($question) {
            if ($question->type === 'multiple_choice') {
                // Obtener opciones originales
                $options = [
                    'a' => $question->option_a,
                    'b' => $question->option_b,
                    'c' => $question->option_c,
                    'd' => $question->option_d
                ];
                
                // Usar semilla fija basada en ID de pregunta para consistencia
                $seed = crc32($question->id . $question->question_text);
                srand($seed);
                
                // Mezclar opciones pero mantener el mapeo
                $shuffledKeys = ['a', 'b', 'c', 'd'];
                shuffle($shuffledKeys);
                
                $shuffledOptions = [];
                $keyMapping = [];
                
                foreach ($shuffledKeys as $index => $originalKey) {
                    $displayKey = ['a', 'b', 'c', 'd'][$index];
                    $shuffledOptions[$displayKey] = $options[$originalKey];
                    
                    // Mapeo: lo que el usuario ve (a,b,c,d) -> lo que realmente es (a,b,c,d)
                    if ($originalKey === $question->correct_option) {
                        $keyMapping[$displayKey] = $question->correct_option;
                    }
                }
                
                // Guardar opciones mezcladas y mapeo en la pregunta
                $question->shuffled_options = $shuffledOptions;
                $question->option_mapping = $keyMapping;
            }
            
            return $question;
        });
        
        return view('assessment.create', compact('questions', 'category'));
    }

    /**
     * Procesar las respuestas y enviar a OpenAI
     */
    public function store(Request $request)
    {
        // Validar que se respondieron todas las preguntas
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        try {
            $answers = $request->input('answers');
            $category = $request->input('category', 'general');
            
            // Si las opciones están mezcladas, necesitamos mapear las respuestas
            $processedAnswers = [];
            foreach ($answers as $questionId => $userAnswer) {
                $question = \App\Models\Question::find($questionId);
                
                if ($question && $question->type === 'multiple_choice') {
                    // Obtener las opciones mezcladas para esta pregunta
                    $options = [
                        'a' => $question->option_a,
                        'b' => $question->option_b,
                        'c' => $question->option_c,
                        'd' => $question->option_d
                    ];
                    
                    // Usar la misma semilla que en el create para consistencia
                    $seed = crc32($question->id . $question->question_text);
                    srand($seed);
                    
                    // Mezclar opciones de la misma manera que en el create
                    $shuffledKeys = ['a', 'b', 'c', 'd'];
                    shuffle($shuffledKeys);
                    
                    $shuffledOptions = [];
                    foreach ($shuffledKeys as $index => $originalKey) {
                        $displayKey = ['a', 'b', 'c', 'd'][$index];
                        $shuffledOptions[$displayKey] = $options[$originalKey];
                    }
                    
                    // Encontrar qué opción original corresponde a la respuesta del usuario
                    $originalOption = null;
                    foreach ($shuffledOptions as $displayKey => $optionText) {
                        if ($displayKey === $userAnswer) {
                            // Encontrar la clave original que corresponde a este texto
                            foreach ($options as $origKey => $origText) {
                                if ($origText === $optionText) {
                                    $originalOption = $origKey;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    
                    $processedAnswers[$questionId] = $originalOption ?: $userAnswer;
                } else {
                    $processedAnswers[$questionId] = $userAnswer;
                }
            }
            
            // Usar el servicio de OpenAI con retry y cache
            $result = $this->openAIService->evaluateAnswers($processedAnswers, $category);

            // Guardar la evaluación en la base de datos
            $assessment = Assessment::create([
                'student_id' => Auth::id(),
                'subject' => $category,
                'answers' => $processedAnswers,
                'detected_level' => $result['level'],
                'ai_recommendation' => $result['recommendation']
            ]);

            return redirect()->route('assessment.result', $assessment->id)
                ->with('success', 'Evaluación completada exitosamente.');

        } catch (\Exception $e) {
            Log::channel('assessment')->error('Error en evaluación: ' . $e->getMessage());
            
            // Manejo específico de errores
            $errorMessage = $this->getErrorMessage($e);
            
            // Si es un error de OpenAI, intentar evaluación local
            if ($this->isOpenAIError($e)) {
                try {
                    $localResult = $this->openAIService->evaluateLocally($answers);
                    
                    $assessment = Assessment::create([
                        'student_id' => Auth::id(),
                        'subject' => $category,
                        'answers' => $answers,
                        'detected_level' => $localResult['level'],
                        'ai_recommendation' => $localResult['recommendation']
                    ]);

                    return redirect()->route('assessment.result', $assessment->id)
                        ->with('success', 'Evaluación completada exitosamente (modo local).');
                        
                } catch (\Exception $localError) {
                    Log::channel('assessment')->error('Error en evaluación local: ' . $localError->getMessage());
                    $errorMessage .= ' También falló la evaluación alternativa. Por favor, contacta al administrador.';
                }
            }
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Mostrar los resultados de la evaluación
     */
    public function result($id)
    {
        $assessment = Assessment::with('student')
            ->where('id', $id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        return view('assessment.result', compact('assessment'));
    }

    /**
     * Obtener mensaje de error específico
     */
    private function getErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        
        return match($message) {
            'RATE_LIMIT' => 'Se ha excedido el límite de peticiones a OpenAI. Por favor, espera unos minutos e intenta nuevamente.',
            'INSUFFICIENT_QUOTA' => 'Se ha excedido la cuota de la API de OpenAI. Por favor, verifica tu plan de facturación o intenta más tarde.',
            'CONNECTION_ERROR' => 'Error de conexión con OpenAI. Por favor, verifica tu conexión a internet e intenta nuevamente.',
            default => 'Hubo un error al procesar tu evaluación. Por favor, intenta nuevamente.'
        };
    }

    /**
     * Verificar si es un error de OpenAI
     */
    private function isOpenAIError(\Exception $e): bool
    {
        $message = $e->getMessage();
        return in_array($message, ['RATE_LIMIT', 'INSUFFICIENT_QUOTA', 'CONNECTION_ERROR', 'OPENAI_ERROR']);
    }
}
