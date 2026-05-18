<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Administrador ARALIN',
            'email' => SeederCatalog::ADMIN_EMAIL,
            'email_verified_at' => now(),
            'password' => UserFactory::password(),
            'role' => 'admin',
            'bio' => 'Cuenta de administración para gestión global de ARALIN.',
            'is_blocked' => false,
        ]);

        foreach (SeederCatalog::TEACHER_NAMES as $index => $name) {
            $n = $index + 1;
            User::query()->create([
                'name' => $name,
                'email' => SeederCatalog::teacherEmail($n),
                'email_verified_at' => now(),
                'password' => UserFactory::password(),
                'role' => 'teacher',
                'bio' => $this->teacherBio($name),
                'profile_photo' => $n <= 5 ? 'https://i.pravatar.cc/150?u=teacher'.$n : null,
                'is_blocked' => $n === 10,
            ]);
        }

        foreach (SeederCatalog::STUDENT_NAMES as $index => $name) {
            $n = $index + 1;
            User::query()->create([
                'name' => $name,
                'email' => SeederCatalog::studentEmail($n),
                'email_verified_at' => now(),
                'password' => UserFactory::password(),
                'role' => 'student',
                'bio' => fake('es_ES')->optional(0.5)->sentence(12),
                'profile_photo' => $n <= 8 ? 'https://i.pravatar.cc/150?u=student'.$n : null,
                'is_blocked' => $n === 30,
            ]);
        }
    }

    private function teacherBio(string $name): string
    {
        $firstName = explode(' ', $name)[0];

        return "Soy {$firstName}, docente con experiencia en clases particulares online y presenciales. "
            .'Especializada en metodología práctica y seguimiento personalizado del alumno.';
    }
}
