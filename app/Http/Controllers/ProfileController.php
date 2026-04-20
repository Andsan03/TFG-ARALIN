<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('profile.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ], [
            'profile_photo.image' => 'El archivo debe ser una imagen.',
            'profile_photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'La imagen no puede ser mayor a 2MB.',
            'new_password.confirmed' => 'Las contraseñas nuevas no coinciden.',
            'current_password.required_with' => 'Debes ingresar tu contraseña actual para cambiarla.',
        ]);

        // Actualizar datos básicos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        // Manejar foto de perfil
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }

            // Subir nueva foto
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }

        // Manejar cambio de contraseña
        if ($request->filled('new_password')) {
            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])
                    ->withInput();
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Eliminar foto de perfil
     */
    public function removePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo) {
            Storage::delete('public/' . $user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return back()
            ->with('success', 'Foto de perfil eliminada correctamente.');
    }

    /**
     * Cerrar sesión del usuario
     */
    public function destroy()
    {
        Auth::logout();
        
        return redirect()
            ->route('home')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
