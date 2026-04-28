<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;

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
            ->map(function($booking) {
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
                    'type' => $booking->status
                ];
            });

        return view('student.dashboard', compact(
            'nextClass',
            'pendingReviews',
            'notifications'
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
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        
        if ($request->filled('modality')) {
            $query->where('modality', $request->input('modality'));
        }
        
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }
        
        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $request->input('max_price'));
        }
        
        // Guardar historial de búsqueda
        if ($request->filled('query')) {
            SearchHistory::create([
                'student_id' => Auth::id(),
                'query' => $request->input('query'),
                'category' => $request->input('category'),
                'modality' => $request->input('modality'),
                'max_price' => $request->input('max_price'),
            ]);
        }
        
        $classes = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Obtener categorías y niveles para filtros
        $categories = Classes::active()->distinct()->pluck('category');
        $levels = ['primaria', 'eso', 'bachillerato', 'universidad', 'otros'];
        $modalities = ['online', 'presential', 'mixed'];
        
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
            'bookings' => function($query) {
                $query->where('status', '!=', 'rechazada');
            }
        ]);
        
        // Verificar si el alumno ya tiene una reserva para esta clase
        $hasBooking = Booking::where('student_id', Auth::id())
            ->where('class_id', $class->id)
            ->exists();
            
        // Verificar si el profesor está en favoritos
        $isFavorite = Favorite::where('student_id', Auth::id())
            ->where('teacher_id', $class->teacher_id)
            ->exists();
        
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
     * Crear reserva de clase (RF-6)
     */
    public function bookClass(Request $request, Classes $class)
    {
        // Verificar si el alumno ya tiene una reserva activa para esta clase
        $existingBooking = Booking::where('student_id', Auth::id())
            ->where('class_id', $class->id)
            ->whereIn('status', ['pendiente', 'aceptada'])
            ->first();
            
        if ($existingBooking) {
            return redirect()->back()
                ->with('error', 'Ya tienes una reserva activa para esta clase.');
        }
        
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'message' => 'nullable|string|max:500',
        ]);
        
        Booking::create([
            'class_id' => $class->id,
            'student_id' => Auth::id(),
            'scheduled_at' => $request->input('scheduled_at'),
            'status' => 'pendiente',
        ]);
        
        return redirect()->route('student.bookings')
            ->with('success', 'Reserva solicitada correctamente. El profesor la revisará pronto.');
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
        if (!in_array($booking->status, ['pendiente', 'aceptada'])) {
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
        $favorites = Favorite::where('student_id', $student->id)
            ->with('teacher.classes', 'teacher.reviewsReceived')
            ->paginate(12);

        return view('student.favorites', compact('favorites'));
    }

    /**
     * Añadir profesor a favoritos
     */
    public function addFavorite($teacherId)
    {
        // Verificar que el usuario exista y sea profesor
        $teacher = \App\Models\User::where('id', $teacherId)
            ->where('role', 'teacher')
            ->firstOrFail();
            
        // Evitar duplicados
        $exists = Favorite::where('student_id', Auth::id())
            ->where('teacher_id', $teacherId)
            ->exists();
            
        if (!$exists) {
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
