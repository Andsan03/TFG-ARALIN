<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Question extends Model
{
    use HasFactory;

    #[Fillable(['subject', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'])]

    // Scopes
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }
}
