<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Assessment extends Model
{
    use HasFactory;

    #[Fillable(['student_id', 'subject', 'detected_level', 'answers', 'ai_recommendation'])]

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }

    // Relaciones
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('detected_level', $level);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
