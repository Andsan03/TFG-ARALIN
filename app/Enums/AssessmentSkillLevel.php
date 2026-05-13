<?php

namespace App\Enums;

/**
 * Nivel devuelto por la evaluación (IA / local). Valores canónicos en BD: principiante, intermedio, avanzado.
 */
enum AssessmentSkillLevel: string
{
    case Principiante = 'principiante';
    case Intermedio = 'intermedio';
    case Avanzado = 'avanzado';

    public function label(): string
    {
        return match ($this) {
            self::Principiante => 'Principiante',
            self::Intermedio => 'Intermedio',
            self::Avanzado => 'Avanzado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Principiante => '#198754',
            self::Intermedio => '#f59e0b',
            self::Avanzado => '#534AB7',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Principiante => 'fas fa-seedling',
            self::Intermedio => 'fas fa-code',
            self::Avanzado => 'fas fa-rocket',
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::Principiante => 'Estás comenzando tu camino. ¡Sigue así!',
            self::Intermedio => 'Tienes conocimientos sólidos. Es hora de desafiarte más.',
            self::Avanzado => '¡Nivel avanzado! Estás listo para proyectos complejos.',
        };
    }

    /**
     * Normaliza valores antiguos (inglés, etapa escolar) o desconocidos.
     */
    public static function normalize(?string $raw): ?self
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $v = strtolower(trim($raw));

        return match ($v) {
            'principiante', 'beginner' => self::Principiante,
            'intermedio', 'intermediate' => self::Intermedio,
            'avanzado', 'advanced' => self::Avanzado,
            'primaria' => self::Principiante,
            'eso' => self::Intermedio,
            'bachillerato' => self::Intermedio,
            'universidad' => self::Avanzado,
            default => self::tryFrom($v),
        };
    }

    /**
     * @return array{color: string, icon: string, text: string, message: string}
     */
    public static function presentation(?string $raw): array
    {
        $level = self::normalize($raw);

        if ($level === null) {
            return [
                'color' => '#1a56db',
                'icon' => 'fas fa-star',
                'text' => ucfirst((string) $raw),
                'message' => '',
            ];
        }

        return [
            'color' => $level->color(),
            'icon' => $level->icon(),
            'text' => $level->label(),
            'message' => $level->message(),
        ];
    }
}
