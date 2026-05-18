<?php

namespace Database\Factories;

use App\Models\User;
use Database\Seeders\Support\SeederCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake('es_ES')->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::password(),
            'role' => 'student',
            'profile_photo' => null,
            'bio' => fake('es_ES')->optional(0.7)->paragraph(2),
            'is_blocked' => false,
            'remember_token' => Str::random(10),
        ];
    }

    public static function password(): string
    {
        return static::$password ??= Hash::make(SeederCatalog::DEMO_PASSWORD);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => 'admin',
            'bio' => 'Administrador de la plataforma ARALIN.',
        ]);
    }

    public function teacher(): static
    {
        return $this->state(fn () => [
            'role' => 'teacher',
            'bio' => fake('es_ES')->paragraph(3),
        ]);
    }

    public function student(): static
    {
        return $this->state(fn () => [
            'role' => 'student',
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn () => ['is_blocked' => true]);
    }

    public function withPhoto(): static
    {
        return $this->state(fn () => [
            'profile_photo' => 'https://i.pravatar.cc/150?u='.fake()->uuid(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
