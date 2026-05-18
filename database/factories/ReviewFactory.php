<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory()->completed(),
            'student_id' => fn (array $attributes) => Booking::find($attributes['booking_id'])?->student_id,
            'teacher_id' => fn (array $attributes) => Booking::find($attributes['booking_id'])?->class?->teacher_id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake('es_ES')->optional(0.15)->sentence(),
        ];
    }

    public function forBooking(Booking $booking): static
    {
        return $this->state(fn () => [
            'booking_id' => $booking->id,
            'student_id' => $booking->student_id,
            'teacher_id' => $booking->class->teacher_id,
        ]);
    }

    public function rating(int $rating): static
    {
        return $this->state(fn () => ['rating' => max(1, min(5, $rating))]);
    }

    public function positive(): static
    {
        return $this->state(fn () => [
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake('es_ES')->paragraph(),
        ]);
    }
}
