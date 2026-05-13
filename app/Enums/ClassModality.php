<?php

namespace App\Enums;

enum ClassModality: string
{
    case Online = 'online';
    case Presencial = 'presencial';
    case Ambas = 'ambas';

    public function label(): string
    {
        return match ($this) {
            self::Online => 'Online',
            self::Presencial => 'Presencial',
            self::Ambas => 'Online y presencial',
        };
    }

    public function faIcon(): string
    {
        return match ($this) {
            self::Online => 'video',
            self::Presencial => 'map-marker-alt',
            self::Ambas => 'globe',
        };
    }

    public function requiresLocation(): bool
    {
        return $this === self::Presencial || $this === self::Ambas;
    }

    public function offersOnlineSessions(): bool
    {
        return $this === self::Online || $this === self::Ambas;
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_map(fn (self $c) => $c->value, self::cases());
    }
}
