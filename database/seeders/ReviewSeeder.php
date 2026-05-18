<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /** Comentarios realistas para pruebas manuales. */
    private array $comments = [
        5 => 'Excelente profesor, explicaciones claras y mucha paciencia. Repetiría sin dudarlo.',
        4 => 'Muy buena clase, contenido útil y buen ritmo. Solo mejoraría la puntualidad.',
        3 => 'Clase correcta, cumplió expectativas básicas pero esperaba más práctica.',
        2 => 'La sesión se retrasó y el material podría estar mejor organizado.',
        1 => 'No coincidió del todo con lo anunciado en la descripción de la clase.',
    ];

    public function run(): void
    {
        $completed = Booking::query()
            ->where('status', 'completada')
            ->with('class')
            ->orderBy('id')
            ->get();

        foreach ($completed as $index => $booking) {
            $student = User::find($booking->student_id);

            if ($this->shouldSkipReview($student, $index)) {
                continue;
            }

            if (Review::query()->where('booking_id', $booking->id)->exists()) {
                continue;
            }

            $rating = ($index % 5) + 1;

            Review::query()->create([
                'booking_id' => $booking->id,
                'student_id' => $booking->student_id,
                'teacher_id' => $booking->class->teacher_id,
                'rating' => $rating,
                'comment' => $this->comments[$rating],
            ]);
        }
    }

    private function shouldSkipReview(?User $student, int $index): bool
    {
        if ($student === null) {
            return $index % 4 === 3;
        }

        if (in_array($student->email, [
            SeederCatalog::studentEmail(1),
            SeederCatalog::studentEmail(3),
        ], true)) {
            return $index % 2 === 0;
        }

        return $index % 4 === 3;
    }
}
