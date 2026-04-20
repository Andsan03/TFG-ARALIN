<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:student,teacher'], // RF-1: Rol obligatorio
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // RNF-1: bcrypt por defecto con Hash::make
            'role' => $data['role'], // RF-1: Guardar rol seleccionado
            'bio' => $data['bio'] ?? null, // Bio opcional
        ]);
    }

    /**
     * Redirigir según el rol después del registro
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            return route('student.dashboard');
        } elseif ($user->role === 'teacher') {
            return route('teacher.dashboard');
        } elseif ($user->role === 'admin') {
            return route('admin.dashboard');
        }
        
        return '/dashboard';
    }
}
