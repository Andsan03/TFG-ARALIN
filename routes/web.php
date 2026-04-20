<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;


// Rutas principales
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticación (usaremos las rutas por defecto de Laravel)
Auth::routes();

// Rutas públicas
Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');

// Rutas de usuario autenticado
Route::middleware(['auth'])->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    Route::post('/logout', [ProfileController::class, 'destroy'])->name('logout');
    
    // Dashboard general (redirige según rol)
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Rutas específicas para ALUMNOS (RF-4, RF-6)
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/search', [StudentController::class, 'searchClasses'])->name('search');
        Route::get('/class/{class}', [StudentController::class, 'showClass'])->name('class.show');
        Route::post('/book/{class}', [StudentController::class, 'bookClass'])->name('book');
        Route::get('/bookings', [StudentController::class, 'bookings'])->name('bookings');
        Route::delete('/bookings/{booking}', [StudentController::class, 'cancelBooking'])->name('bookings.cancel');
        Route::get('/review/{booking}', [StudentController::class, 'createReview'])->name('review.create');
        Route::post('/review/{booking}', [StudentController::class, 'storeReview'])->name('review.store');
        Route::get('/favorites', [StudentController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{teacher}', [StudentController::class, 'addFavorite'])->name('favorites.add');
        Route::delete('/favorites/{teacher}', [StudentController::class, 'removeFavorite'])->name('favorites.remove');
        Route::get('/search-history', [StudentController::class, 'searchHistory'])->name('search-history');
    });
    
    // Rutas específicas para PROFESORES (RF-11, RF-12)
    Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes', [TeacherController::class, 'classes'])->name('classes');
        Route::get('/classes/create', [TeacherController::class, 'createClass'])->name('classes.create');
        Route::post('/classes', [TeacherController::class, 'storeClass'])->name('classes.store');
        Route::get('/classes/{class}/edit', [TeacherController::class, 'editClass'])->name('classes.edit');
        Route::put('/classes/{class}', [TeacherController::class, 'updateClass'])->name('classes.update');
        Route::post('/classes/{class}/toggle', [TeacherController::class, 'toggleClass'])->name('classes.toggle');
        Route::delete('/classes/{class}', [TeacherController::class, 'destroyClass'])->name('classes.destroy');
        Route::get('/bookings', [TeacherController::class, 'bookings'])->name('bookings');
        Route::post('/bookings/{booking}/accept', [TeacherController::class, 'acceptBooking'])->name('bookings.accept');
        Route::post('/bookings/{booking}/reject', [TeacherController::class, 'rejectBooking'])->name('bookings.reject');
        Route::post('/bookings/{booking}/complete', [TeacherController::class, 'completeBooking'])->name('bookings.complete');
        Route::get('/reviews', [TeacherController::class, 'reviews'])->name('reviews');
    });
    
    // Rutas de administrador
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('/users', AdminController::class)->names('users');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/classes/{class}', [AdminController::class, 'destroyClass'])->name('classes.destroy');
    });
});
