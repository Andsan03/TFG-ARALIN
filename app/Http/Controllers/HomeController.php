<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the ARALIN homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener clases destacadas para la página principal
        $featuredClasses = Classes::active()->take(6)->get();
        
        return view('home', compact('featuredClasses'));
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Redirigir según el rol del usuario
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Si no tiene rol válido, redirigir al home
        return redirect()->route('home');
    }
}
