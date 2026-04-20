<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Booking extends Model
{
    use HasFactory;

    #[Fillable(['class_id', 'student_id', 'assessment_id', 'status', 'scheduled_at', 'meeting_url'])]

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    // Relaciones
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'aceptada');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completada');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now());
    }
}
