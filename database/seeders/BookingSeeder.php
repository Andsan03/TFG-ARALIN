<?php

namespace Database\Seeders;

use App\Enums\ClassModality;
use App\Models\Booking;
use App\Models\Classes;
use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::students()->orderBy('id')->get();
        $classes = Classes::with('teacher')->get();

        $this->seedShowcaseBookings($students, $classes);
        $this->seedDistributedBookings($students, $classes);
    }

    private function seedShowcaseBookings($students, $classes): void
    {
        $student1 = $students->first();
        $student2 = $students->skip(1)->first();
        $student3 = $students->skip(2)->first();

        $onlineClass = $classes->first(fn ($c) => $c->modality === ClassModality::Online && $c->is_active);
        $presencialClass = $classes->first(fn ($c) => $c->modality === ClassModality::Presencial && $c->is_active);
        $ambasClass = $classes->first(fn ($c) => $c->modality === ClassModality::Ambas && $c->is_active);

        if ($onlineClass && $student1) {
            $this->createBooking($onlineClass, $student1, 'pendiente', now()->addDays(5), ClassModality::Online->value, null);
            $this->createBooking($onlineClass, $student2, 'aceptada', now()->addDays(3), ClassModality::Online->value, SeederCatalog::jitsiMeetingUrl(1001));
            $this->createBooking($onlineClass, $student3, 'completada', now()->subDays(10), ClassModality::Online->value, SeederCatalog::jitsiMeetingUrl(1002));
        }

        if ($presencialClass && $student1) {
            $this->createBooking($presencialClass, $student1, 'aceptada', now()->addDays(7), ClassModality::Presencial->value, null);
            $this->createBooking($presencialClass, $student2, 'rechazada', now()->addDays(2), ClassModality::Presencial->value, null);
        }

        if ($ambasClass && $student2) {
            $this->createBooking($ambasClass, $student2, 'pendiente', now()->addDays(4), ClassModality::Online->value, null);
            $this->createBooking($ambasClass, $student3, 'aceptada', now()->addDays(6), ClassModality::Presencial->value, null);
        }
    }

    private function seedDistributedBookings($students, $classes): void
    {
        $statuses = ['pendiente', 'aceptada', 'rechazada', 'completada'];
        $usedActivePairs = [];

        foreach ($students as $studentIndex => $student) {
            $studentClasses = $classes->shuffle()->take(8);

            foreach ($studentClasses as $classIndex => $class) {
                $status = $statuses[($studentIndex + $classIndex) % 4];
                $pairKey = $student->id.'-'.$class->id;

                if (in_array($status, ['pendiente', 'aceptada'], true)) {
                    if (isset($usedActivePairs[$pairKey])) {
                        continue;
                    }
                    $usedActivePairs[$pairKey] = true;
                }

                $bookingModality = $this->bookingModalityFor($class, $classIndex);
                $scheduledAt = $this->scheduledAtFor($status, $studentIndex, $classIndex);
                $meetingUrl = $this->meetingUrlFor($status, $bookingModality);

                $this->createBooking($class, $student, $status, $scheduledAt, $bookingModality, $meetingUrl);
            }
        }
    }

    private function createBooking(
        Classes $class,
        User $student,
        string $status,
        Carbon $scheduledAt,
        string $bookingModality,
        ?string $meetingUrl
    ): Booking {
        return Booking::query()->create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'assessment_id' => null,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'booking_modality' => $bookingModality,
            'meeting_url' => $meetingUrl,
        ]);
    }

    private function bookingModalityFor(Classes $class, int $offset): string
    {
        $classModality = $class->modality instanceof ClassModality
            ? $class->modality->value
            : (string) $class->modality;

        return match ($classModality) {
            ClassModality::Online->value => ClassModality::Online->value,
            ClassModality::Presencial->value => ClassModality::Presencial->value,
            default => $offset % 2 === 0 ? ClassModality::Online->value : ClassModality::Presencial->value,
        };
    }

    private function scheduledAtFor(string $status, int $studentIndex, int $classIndex): Carbon
    {
        return match ($status) {
            'completada' => now()->subDays(5 + (($studentIndex + $classIndex) % 40)),
            'rechazada' => now()->subDays(1 + ($classIndex % 5)),
            'aceptada' => now()->addDays(1 + (($studentIndex + $classIndex) % 14)),
            default => now()->addDays(2 + (($studentIndex + $classIndex) % 21)),
        };
    }

    private function meetingUrlFor(string $status, string $bookingModality): ?string
    {
        if ($status !== 'aceptada' || $bookingModality !== ClassModality::Online->value) {
            return null;
        }

        return SeederCatalog::jitsiMeetingUrl(random_int(2000, 999999));
    }
}
