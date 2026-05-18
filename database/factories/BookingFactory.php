<?php

namespace Database\Factories;

use App\Enums\ClassModality;
use App\Models\Booking;
use App\Models\Classes;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $class = Classes::factory()->create();
        $bookingModality = $this->resolveBookingModality($class);

        return [
            'class_id' => $class->id,
            'student_id' => User::factory()->student(),
            'assessment_id' => null,
            'status' => 'pendiente',
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'meeting_url' => null,
            'booking_modality' => $bookingModality,
        ];
    }

    public function forClass(Classes $class): static
    {
        return $this->state(function () use ($class) {
            $bookingModality = $this->resolveBookingModality($class);

            return [
                'class_id' => $class->id,
                'booking_modality' => $bookingModality,
            ];
        });
    }

    public function forStudent(User|int $student): static
    {
        $studentId = $student instanceof User ? $student->id : $student;

        return $this->state(fn () => ['student_id' => $studentId]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pendiente',
            'meeting_url' => null,
            'scheduled_at' => fake()->dateTimeBetween('+2 days', '+20 days'),
        ]);
    }

    public function accepted(): static
    {
        return $this->state(function (array $attributes) {
            $class = Classes::find($attributes['class_id'] ?? null);
            $modality = (string) ($attributes['booking_modality'] ?? $class?->modality?->value ?? 'online');

            return [
                'status' => 'aceptada',
                'scheduled_at' => fake()->dateTimeBetween('+1 day', '+14 days'),
                'meeting_url' => $modality === ClassModality::Online->value
                    ? SeederCatalog::jitsiMeetingUrl(fake()->numberBetween(1, 999999))
                    : null,
            ];
        });
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'rechazada',
            'meeting_url' => null,
            'scheduled_at' => fake()->dateTimeBetween('-14 days', '+7 days'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completada',
            'meeting_url' => null,
            'scheduled_at' => fake()->dateTimeBetween('-60 days', '-1 day'),
        ]);
    }

    public function onlineModality(): static
    {
        return $this->state(fn () => ['booking_modality' => ClassModality::Online->value]);
    }

    public function presencialModality(): static
    {
        return $this->state(fn () => ['booking_modality' => ClassModality::Presencial->value]);
    }

    public function inPast(): static
    {
        return $this->state(fn () => [
            'scheduled_at' => fake()->dateTimeBetween('-45 days', '-1 day'),
        ]);
    }

    public function inFuture(): static
    {
        return $this->state(fn () => [
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+45 days'),
        ]);
    }

    private function resolveBookingModality(Classes $class): string
    {
        $classModality = $class->modality instanceof ClassModality
            ? $class->modality->value
            : (string) $class->modality;

        return match ($classModality) {
            ClassModality::Online->value => ClassModality::Online->value,
            ClassModality::Presencial->value => ClassModality::Presencial->value,
            default => fake()->randomElement([ClassModality::Online->value, ClassModality::Presencial->value]),
        };
    }
}
