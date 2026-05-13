<?php

namespace App\Http\Controllers;

use App\Enums\ClassLevel;
use App\Enums\ClassModality;
use App\Models\Assessment;
use App\Models\Booking;
use App\Models\Classes;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Dashboard del alumno
     */
    public function dashboard()
    {
        $student = Auth::user();

        // PRÓXIMA CLASE - La más cercana en fecha futura
        $nextClass = Booking::where('student_id', $student->id)
            ->where('status', 'aceptada')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', now())
            ->with('class.teacher')
            ->orderBy('scheduled_at', 'asc')
            ->first();

        // VALORACIONES PENDIENTES - Reservas completadas sin review
        $pendingReviews = Booking::where('student_id', $student->id)
            ->where('status', 'completada')
            ->whereDoesntHave('review')
            ->with('class.teacher')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // NOTIFICACIONES - Últimos cambios en reservas
        $notifications = Booking::where('student_id', $student->id)
            ->whereIn('status', ['aceptada', 'rechazada', 'completada'])
            ->with('class.teacher')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($booking) {
                $message = '';
                if ($booking->status === 'aceptada') {
                    $message = "Tu clase de {$booking->class->title} fue aceptada por {$booking->class->teacher->name}";
                } elseif ($booking->status === 'rechazada') {
                    $message = "Tu clase de {$booking->class->title} fue rechazada";
                } elseif ($booking->status === 'completada') {
                    $message = "Tu clase de {$booking->class->title} fue completada";
                }

                return [
                    'message' => $message,
                    'date' => $booking->updated_at,
                    'type' => $booking->status,
                ];
            });

        // NIVELES DE EVALUACIÓN - Obtener evaluaciones por categoría
        $assessments = Assessment::where('student_id', $student->id)
            ->select('subject', 'detected_level', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('subject')
            ->map(function ($assessmentsBySubject, $subject) {
                $latestAssessment = $assessmentsBySubject->first();

                return [
                    'subject' => $subject,
                    'level' => $latestAssessment->detected_level,
                    'evaluated_at' => $latestAssessment->created_at,
                    'evaluations_count' => $assessmentsBySubject->count(),
                ];
            });

        return view('student.dashboard', compact(
            'nextClass',
            'pendingReviews',
            'notifications',
            'assessments'
        ));
    }

    /**
     * Buscar clases (RF-4)
     */
    public function searchClasses(Request $request)
    {
        $query = Classes::active()->with('teacher.reviewsReceived');

        // Filtros de búsqueda
        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('modality')) {
            $modality = ClassModality::tryFrom((string) $request->input('modality'));
            if ($modality) {
                $query->where('modality', $modality->value);
            }
        }

        if ($request->filled('level')) {
            $level = ClassLevel::tryFrom((string) $request->input('level'));
            if ($level) {
                $query->where('level', $level->value);
            }
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $request->input('max_price'));
        }

        // Guardar historial de búsqueda
        if ($request->filled('query')) {
            $modalityForHistory = ClassModality::tryFrom((string) $request->input('modality', ''))?->value;
            SearchHistory::create([
                'student_id' => Auth::id(),
                'query' => $request->input('query'),
                'category' => $request->input('category'),
                'modality' => $modalityForHistory,
                'max_price' => $request->input('max_price'),
            ]);
        }

        $classes = $query->orderBy('created_at', 'desc')->paginate(12);

        // Obtener categorías y niveles para filtros
        $categories = Classes::active()->distinct()->pluck('category');
        $levels = ClassLevel::values();
        $modalities = ClassModality::values();

        return view('student.search', compact(
            'classes',
            'categories',
            'levels',
            'modalities'
        ));
    }

    /**
     * Ver detalles de clase
     */
    public function showClass(Classes $class)
    {
        // Cargar relaciones adicionales
        $class->load([
            'teacher.reviewsReceived',
            'bookings' => function ($query) {
                $query->whereIn('status', ['pendiente', 'aceptada']);
            },
        ]);

        // Verificar si el alumno ya tiene una reserva ACTIVA para esta clase
        $hasBooking = Auth::check()
            ? Booking::hasActiveBooking(Auth::id(), $class->id)
            : false;

        // Verificar si el profesor está en favoritos
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Favorite::where('student_id', Auth::id())
                ->where('teacher_id', $class->teacher_id)
                ->exists();
        }

        // Calcular rating promedio del profesor
        $averageRating = Review::where('teacher_id', $class->teacher_id)->avg('rating');

        return view('student.class-detail', compact(
            'class',
            'hasBooking',
            'isFavorite',
            'averageRating'
        ));
    }

    /**
     * Mostrar formulario de reserva
     */
    public function createBooking(Classes $class)
    {
        // Verificar si el alumno ya tiene una reserva ACTIVA para esta clase
        // Solo se consideran activas las reservas pendientes o aceptadas (NO las completadas/rechazadas)
        $hasBooking = Booking::hasActiveBooking(Auth::id(), $class->id);

        if ($hasBooking) {
            return redirect()->route('student.class.show', $class)
                ->with('error', 'Ya tienes una reserva activa para esta clase.');
        }

        // Cargar relaciones
        $class->load('teacher');

        return view('student.book-create', compact('class'));
    }

    /**
     * Crear reserva de clase (RF-6)
     */
    public function bookClass(Request $request, Classes $class)
    {
        // Logging para diagnóstico
        Log::info('Intento de reserva - Usuario: '.Auth::id().' - Clase: '.$class->id.' - Título: '.$class->title);
        Log::info('Datos recibidos: ', $request->all());

        // Verificar si el alumno ya tiene una reserva ACTIVA para esta clase
        // Solo se consideran activas las reservas pendientes o aceptadas (NO las completadas)
        $hasBooking = Booking::hasActiveBooking(Auth::id(), $class->id);

        // Logging del resultado de la búsqueda
        if ($hasBooking) {
            // Obtener la reserva activa para logging
            $activeBooking = Booking::where('student_id', Auth::id())
                ->where('class_id', $class->id)
                ->whereIn('status', ['pendiente', 'aceptada'])
                ->first();

            Log::warning('Reserva activa encontrada - ID: '.$activeBooking->id.' - Estado: '.$activeBooking->status);

            return redirect()->back()
                ->with('error', 'Ya tienes una reserva activa para esta clase.');
        } else {
            Log::info('No se encontraron reservas activas - Permitiendo nueva reserva');
        }

        $allowedBookingModalities = match ($class->modality) {
            ClassModality::Online => ['online'],
            ClassModality::Presencial => ['presencial'],
            ClassModality::Ambas => ['online', 'presencial'],
        };

        try {
            $validated = $request->validate([
                'scheduled_at' => 'required|date|after:now',
                'message' => 'nullable|string|max:500',
                'booking_modality' => ['required', 'string', Rule::in($allowedBookingModalities)],
            ]);

            Log::info('Validación exitosa: ', $validated);
        } catch (ValidationException $e) {
            Log::error('Error de validación: ', $e->errors());

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Usar la modalidad seleccionada por el usuario
        $bookingModality = $request->input('booking_modality');

        try {
            Log::info('Intentando crear reserva con datos: ', [
                'class_id' => $class->id,
                'student_id' => Auth::id(),
                'scheduled_at' => $request->input('scheduled_at'),
                'status' => 'pendiente',
                'booking_modality' => $bookingModality,
            ]);

            $booking = Booking::create([
                'class_id' => $class->id,
                'student_id' => Auth::id(),
                'scheduled_at' => $request->input('scheduled_at'),
                'status' => 'pendiente',
                'booking_modality' => $bookingModality,
            ]);

            Log::info('Reserva creada exitosamente - ID: '.$booking->id);

            return redirect()->route('student.bookings')
                ->with('success', 'Reserva solicitada correctamente. El profesor la revisará pronto.');

        } catch (\Exception $e) {
            Log::error('Error al crear reserva: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Ha ocurrido un error al procesar tu reserva. Por favor, inténtalo de nuevo.')
                ->withInput();
        }
    }

    /**
     * Ver mis reservas (RF-6)
     */
    public function bookings()
    {
        $student = Auth::user();
        $bookings = Booking::where('student_id', $student->id)
            ->with('class.teacher', 'review')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('student.bookings', compact('bookings'));
    }

    /**
     * Cancelar reserva
     */
    public function cancelBooking(Booking $booking)
    {
        // Verificar que la reserva pertenezca al alumno
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // Solo se pueden cancelar reservas pendientes o aceptadas
        if (! in_array($booking->status, ['pendiente', 'aceptada'])) {
            return redirect()->back()
                ->with('error', 'Solo se pueden cancelar reservas pendientes o aceptadas.');
        }

        $booking->update(['status' => 'rechazada']);

        return redirect()->route('student.bookings')
            ->with('success', 'Reserva cancelada correctamente.');
    }

    /**
     * Dejar reseña de clase completada
     */
    public function createReview(Booking $booking)
    {
        // Verificar que la reserva pertenezca al alumno y esté completada
        if ($booking->student_id !== Auth::id() || $booking->status !== 'completada') {
            abort(403, 'No puedes reseñar esta reserva.');
        }

        // Verificar que no tenga reseña ya
        if ($booking->review) {
            return redirect()->back()
                ->with('error', 'Ya has dejado una reseña para esta clase.');
        }

        return view('student.create-review', compact('booking'));
    }

    /**
     * Guardar reseña
     */
    public function storeReview(Request $request, Booking $booking)
    {
        // Verificar que la reserva pertenezca al alumno y esté completada
        if ($booking->student_id !== Auth::id() || $booking->status !== 'completada') {
            abort(403, 'No puedes reseñar esta reserva.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'student_id' => Auth::id(),
            'teacher_id' => $booking->class->teacher_id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->route('student.bookings')
            ->with('success', 'Reseña publicada correctamente. ¡Gracias!');
    }

    /**
     * Gestionar profesores favoritos
     */
    public function favorites()
    {
        $student = Auth::user();
        $favoriteTeachers = Favorite::where('student_id', $student->id)
            ->with('teacher.classes', 'teacher.reviewsReceived')
            ->get()
            ->pluck('teacher');

        return view('student.favorites', ['favoriteTeachers' => $favoriteTeachers]);
    }

    /**
     * Añadir profesor a favoritos
     */
    public function addFavorite($teacherId)
    {
        // Verificar que el usuario exista y sea profesor
        $teacher = User::where('id', $teacherId)
            ->where('role', 'teacher')
            ->firstOrFail();

        // Evitar duplicados
        $exists = Favorite::where('student_id', Auth::id())
            ->where('teacher_id', $teacherId)
            ->exists();

        if (! $exists) {
            Favorite::create([
                'student_id' => Auth::id(),
                'teacher_id' => $teacherId,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Profesor añadido a favoritos.');
    }

    /**
     * Eliminar profesor de favoritos
     */
    public function removeFavorite($teacherId)
    {
        Favorite::where('student_id', Auth::id())
            ->where('teacher_id', $teacherId)
            ->delete();

        return redirect()->back()
            ->with('success', 'Profesor eliminado de favoritos.');
    }

    /**
     * Ver historial de búsquedas
     */
    public function searchHistory()
    {
        $student = Auth::user();
        $searches = SearchHistory::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.search-history', compact('searches'));
    }
}
