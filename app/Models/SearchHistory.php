<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class SearchHistory extends Model
{
    use HasFactory;

    #[Fillable(['student_id', 'query', 'category', 'modality', 'max_price'])]

    protected function casts(): array
    {
        return [
            'max_price' => 'decimal:2',
        ];
    }

    // Relaciones
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
