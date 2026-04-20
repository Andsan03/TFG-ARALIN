<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        // Verificar si el usuario tiene el rol requerido
        $user = Auth::user();
        
        // Si el usuario está bloqueado, denegar acceso
        if ($user->is_blocked) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido bloqueada. Contacta con el administrador.');
        }

        // Verificar roles permitidos
        if (!in_array($user->role, $roles)) {
            // Redirigir según el rol del usuario
            return match($user->role) {
                'student' => redirect()->route('student.dashboard')->with('error', 'Acceso denegado. Esta sección es solo para profesores.'),
                'teacher' => redirect()->route('teacher.dashboard')->with('error', 'Acceso denegado. Esta sección es solo para alumnos.'),
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Acceso denegado.'),
                default => redirect()->route('home')->with('error', 'Rol no reconocido.'),
            };
        }

        return $next($request);
    }
}
