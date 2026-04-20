<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Importante para el RF-1 
        'profile_photo',
        'bio',
    ];
    
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blocked' => 'boolean',
        ];
    }

    // Relaciones
    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'teacher_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'student_id');
    }

    public function favoriteTeachers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'student_id', 'teacher_id');
    }

    public function favoriteStudents()
    {
        return $this->belongsToMany(User::class, 'favorites', 'teacher_id', 'student_id');
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class, 'student_id');
    }

    // Scopes
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }
}
