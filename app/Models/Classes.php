<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Classes extends Model
{
    use HasFactory;

    #[Fillable(['title', 'description', 'category', 'modality', 'price_per_hour', 'level', 'is_active'])]

    protected function casts(): array
    {
        return [
            'price_per_hour' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relaciones
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByModality($query, $modality)
    {
        return $query->where('modality', $modality);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price_per_hour', [$min, $max]);
    }
}
