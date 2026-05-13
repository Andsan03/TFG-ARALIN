<?php

namespace App\Http\Controllers;

use App\Enums\ClassLevel;
use App\Enums\ClassModality;
use App\Models\Booking;
use App\Models\Classes;
use App\Models\Review;
use App\Services\VideoCallService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    protected $videoCallService;

    public function __construct(VideoCallService $videoCallService)
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
        $this->videoCallService = $videoCallService;
    }

    /**
     * Dashboard del profesor
     */
    public function dashboard()
    {
        $teacher = Auth::user();

        // Estadísticas del profesor
        $totalClasses = Classes::where('teacher_id', $teacher->id)->count();
        $activeClasses = Classes::where('teacher_id', $teacher->id)->where('is_active', true)->count();
        $totalBookings = Booking::whereHas('class', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->count();
        $pendingBookings = Booking::whereHas('class', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('status', 'pendiente')->count();

        // Clases y reservas recientes
        $recentClasses = Classes::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentBookings = Booking::whereHas('class', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('student', 'class')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'totalClasses',
            'activeClasses',
            'totalBookings',
            'pendingBookings',
            'recentClasses',
            'recentBookings'
        ));
    }

    /**
     * Listar clases del profesor (RF-11)
     */
    public function classes()
    {
        $teacher = Auth::user();
        $classes = Classes::where('teacher_id', $teacher->id)
            ->withCount(['bookings' => function ($query) {
                $query->where('status', '!=', 'rechazada');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.classes.index', compact('classes'));
    }

    /**
     * Formulario crear clase (RF-11)
     */
    public function createClass()
    {
        return view('teacher.classes.create');
    }

    /**
     * Guardar nueva clase (RF-11)
     */
    public function storeClass(Request $request)
    {
        // Validaciones base
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|in:matematicas,ciencias,idiomas,arte,musica,deporte,programacion,negocios,otros',
            'modality' => ['required', Rule::enum(ClassModality::class)],
            'price_per_hour' => 'required|numeric|min:1|max:999',
            'level' => ['required', Rule::enum(ClassLevel::class)],
        ];

        $modality = ClassModality::tryFrom((string) $request->input('modality', ''));
        if ($modality?->requiresLocation()) {
            $validationRules['location'] = 'required|string|max:255';
        }

        $request->validate($validationRules);

        Classes::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'modality' => $request->modality,
            'price_per_hour' => $request->price_per_hour,
            'level' => $request->level,
            'location' => $request->location,
            'is_active' => true,
        ]);

        return redirect()->route('teacher.classes')
            ->with('success', 'Clase creada correctamente.');
    }

    /**
     * Mostrar formulario para editar clase
     */
    public function edit(Classes $class)
    {
        // Verificar que la clase pertenezca al profesor autenticado
        if ($class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta clase.');
        }

        return view('teacher.classes.edit', compact('class'));
    }

    /**
     * Actualizar clase existente
     */
    public function update(Request $request, Classes $class)
    {
        // Verificar que la clase pertenezca al profesor autenticado
        if ($class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta clase.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|in:matematicas,ciencias,idiomas,arte,musica,deporte,programacion,negocios,otros',
            'level' => ['required', Rule::enum(ClassLevel::class)],
            'price_per_hour' => 'required|numeric|min:1|max:999',
            'modality' => ['required', Rule::enum(ClassModality::class)],
            'location' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(function () use ($request) {
                    $m = ClassModality::tryFrom((string) $request->input('modality', ''));

                    return $m?->requiresLocation() ?? false;
                }),
            ],
        ]);

        $class->update($validated);

        return redirect()->route('teacher.classes')
            ->with('success', 'Clase actualizada correctamente.');
    }

    /**
     * Activar/Desactivar clase (RF-12)
     */
    public function toggleClass(Classes $class)
    {
        // Verificar que la clase pertenezca al profesor
        if ($class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar esta clase.');
        }

        $class->update(['is_active' => ! $class->is_active]);

        $status = $class->is_active ? 'activada' : 'desactivada';

        return redirect()->route('teacher.classes')
            ->with('success', "Clase {$status} correctamente.");
    }

    /**
     * Eliminar clase (RF-12)
     */
    public function destroyClass(Classes $class)
    {
        // Verificar que la clase pertenezca al profesor
        if ($class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta clase.');
        }

        // Verificar que no tenga reservas activas
        $activeBookings = $class->bookings()->whereIn('status', ['pendiente', 'aceptada'])->count();
        if ($activeBookings > 0) {
            return redirect()->route('teacher.classes')
                ->with('error', 'No puedes eliminar una clase con reservas activas.');
        }

        $class->delete();

        return redirect()->route('teacher.classes')
            ->with('success', 'Clase eliminada correctamente.');
    }

    /**
     * Ver reservas de clases del profesor (RF-12)
     */
    public function bookings()
    {
        $teacher = Auth::user();
        $bookings = Booking::whereHas('class', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('student', 'class')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('teacher.bookings', compact('bookings'));
    }

    /**
     * Aceptar reserva (RF-12)
     */
    public function acceptBooking(Booking $booking)
    {
        // Verificar que la reserva sea de una clase del profesor
        if ($booking->class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar esta reserva.');
        }

        // Actualizar estado de la reserva
        $booking->update(['status' => 'aceptada']);

        // Generar automáticamente enlace de videollamada si aplica
        $meetingUrl = $this->videoCallService->generateMeetingUrl($booking);

        // Preparar mensaje de éxito
        $successMessage = 'Reserva aceptada correctamente.';
        if ($meetingUrl) {
            $successMessage .= ' Se ha generado el enlace de videollamada automáticamente.';
        }

        return redirect()->route('teacher.bookings')
            ->with('success', $successMessage);
    }

    /**
     * Rechazar reserva (RF-12)
     */
    public function rejectBooking(Booking $booking)
    {
        // Verificar que la reserva sea de una clase del profesor
        if ($booking->class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar esta reserva.');
        }

        $booking->update(['status' => 'rechazada']);

        return redirect()->route('teacher.bookings')
            ->with('success', 'Reserva rechazada correctamente.');
    }

    /**
     * Marcar reserva como completada (RF-12)
     */
    public function completeBooking(Booking $booking)
    {
        // Verificar que la reserva sea de una clase del profesor
        if ($booking->class->teacher_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar esta reserva.');
        }

        $booking->update(['status' => 'completada']);

        return redirect()->route('teacher.bookings')
            ->with('success', 'Reserva marcada como completada.');
    }

    /**
     * Ver reseñas recibidas
     */
    public function reviews()
    {
        $teacher = Auth::user();
        $reviews = Review::where('teacher_id', $teacher->id)
            ->with('student', 'booking')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calcular promedio de valoraciones
        $averageRating = Review::where('teacher_id', $teacher->id)->avg('rating');

        return view('teacher.reviews', compact('reviews', 'averageRating'));
    }
}
