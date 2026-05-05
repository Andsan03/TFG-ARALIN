<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Mostrar dashboard del administrador con estadísticas
     */
    public function dashboard()
    {
        // Estadísticas básicas para el dashboard
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_classes' => Classes::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pendiente')->count(),
            'total_reviews' => Review::count(),
        ];

        // Obtener usuarios recientes para mostrar en tabla
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        // Obtener clases recientes
        $recentClasses = Classes::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentClasses'));
    }

    /**
     * Listar todos los usuarios
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Bloquear un usuario
     */
    public function blockUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Evitar bloquear a otros administradores
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'No se puede bloquear a otro administrador.');
        }

        $user->is_blocked = true;
        $user->save();

        return redirect()->back()->with('success', "Usuario {$user->name} bloqueado correctamente.");
    }

    /**
     * Desbloquear un usuario
     */
    public function unblockUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_blocked = false;
        $user->save();

        return redirect()->back()->with('success', "Usuario {$user->name} desbloqueado correctamente.");
    }

    /**
     * Listar todas las clases
     */
    public function classes()
    {
        $classes = Classes::with('teacher')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.classes', compact('classes'));
    }

    /**
     * Eliminar una clase inapropiada
     */
    public function deleteClass($classId)
    {
        $class = Classes::findOrFail($classId);
        $className = $class->title;
        
        // Eliminar también las reservas y reseñas asociadas
        $class->bookings()->delete();
        $class->reviews()->delete();
        $class->delete();

        return redirect()->back()->with('success', "Clase '{$className}' eliminada correctamente.");
    }

    /**
     * Listar todas las reseñas
     */
    public function reviews()
    {
        $reviews = Review::with(['booking.student', 'booking.class.teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews', compact('reviews'));
    }

    /**
     * Eliminar una reseña inapropiada
     */
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return redirect()->back()->with('success', 'Reseña eliminada correctamente.');
    }
}
