<?php

namespace App\Casts;

use App\Enums\ClassModality;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Normaliza valores antiguos de BD (presential, mixed) al enum canónico.
 */
class ClassModalityCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ClassModality
    {
        if ($value === null || $value === '') {
            return null;
        }

        $canonical = self::toCanonical((string) $value);

        return ClassModality::tryFrom($canonical) ?? ClassModality::Online;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value instanceof ClassModality) {
            return $value->value;
        }

        if ($value === null || $value === '') {
            return null;
        }

        $canonical = self::toCanonical((string) $value);

        return ClassModality::tryFrom($canonical)?->value ?? ClassModality::Online->value;
    }

    private static function toCanonical(string $raw): string
    {
        return match (strtolower(trim($raw))) {
            'presential' => ClassModality::Presencial->value,
            'mixed' => ClassModality::Ambas->value,
            default => $raw,
        };
    }
}
