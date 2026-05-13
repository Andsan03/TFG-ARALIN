<?php

namespace App\Services;

use App\Enums\ClassModality;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoCallService
{
    /**
     * URL base para Jitsi Meet (público y gratuito)
     */
    private const JITSI_BASE_URL = 'https://meet.jit.si/';

    /**
     * Longitud del identificador único de sala
     */
    private const ROOM_ID_LENGTH = 16;

    /**
     * Prefijo para las salas de ARALIN
     */
    private const ROOM_PREFIX = 'aralin-';

    /**
     * Generar un enlace de videollamada único para una reserva
     *
     * @return string|null URL de la videollamada o null si no aplica
     */
    public function generateMeetingUrl(Booking $booking): ?string
    {
        try {
            // Verificar que la reserva aceptada y requiera videollamada
            if (! $this->shouldGenerateMeetingUrl($booking)) {
                Log::channel('videocalls')->info('No se genera URL: reserva no aplica para videollamada', [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'modality' => $booking->booking_modality ?? $booking->class->modality->value,
                ]);

                return null;
            }

            // Evitar generar múltiples URLs para la misma reserva
            if ($booking->meeting_url) {
                Log::channel('videocalls')->info('Reserva ya tiene URL asignada', [
                    'booking_id' => $booking->id,
                    'existing_url' => $booking->meeting_url,
                ]);

                return $booking->meeting_url;
            }

            // Generar identificador único y seguro
            $roomName = $this->generateUniqueRoomName($booking);
            $meetingUrl = self::JITSI_BASE_URL.$roomName;

            // Guardar URL en la reserva
            $booking->update(['meeting_url' => $meetingUrl]);

            Log::channel('videocalls')->info('URL de videollamada generada exitosamente', [
                'booking_id' => $booking->id,
                'room_name' => $roomName,
                'meeting_url' => $meetingUrl,
                'class_title' => $booking->class->title,
                'student_name' => $booking->student->name,
                'teacher_name' => $booking->class->teacher->name,
            ]);

            return $meetingUrl;

        } catch (\Exception $e) {
            Log::channel('videocalls')->error('Error generando URL de videollamada', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Verificar si una reserva debe tener URL de videollamada
     */
    private function shouldGenerateMeetingUrl(Booking $booking): bool
    {
        // Solo para reservas aceptadas
        if ($booking->status !== 'aceptada') {
            return false;
        }

        $raw = (string) ($booking->booking_modality ?? $booking->class->modality->value);

        return ClassModality::tryFrom($raw) === ClassModality::Online;
    }

    /**
     * Generar un nombre de sala único y seguro
     */
    private function generateUniqueRoomName(Booking $booking): string
    {
        // Componentes para hacer el nombre único pero legible
        $components = [
            self::ROOM_PREFIX,
            // ID de la reserva (últimos 4 dígitos)
            substr($booking->id, -4),
            // Hash aleatorio para garantizar unicidad
            Str::random(self::ROOM_ID_LENGTH - 4 - strlen(self::ROOM_PREFIX)),
        ];

        return strtolower(implode('', $components));
    }

    /**
     * Validar si un URL de videollamada es válido
     */
    public function isValidMeetingUrl(string $url): bool
    {
        return str_starts_with($url, self::JITSI_BASE_URL) &&
               str_contains($url, self::ROOM_PREFIX);
    }

    /**
     * Obtener información de la sala desde un URL
     */
    public function extractRoomInfo(string $url): ?array
    {
        if (! $this->isValidMeetingUrl($url)) {
            return null;
        }

        $roomName = str_replace(self::JITSI_BASE_URL, '', $url);

        return [
            'room_name' => $roomName,
            'full_url' => $url,
            'is_aralin_room' => str_starts_with($roomName, self::ROOM_PREFIX),
            'jitsi_url' => self::JITSI_BASE_URL,
        ];
    }

    /**
     * Generar URL para invitado (sin requerir login)
     */
    public function generateGuestUrl(string $meetingUrl, string $displayName): string
    {
        $separator = str_contains($meetingUrl, '?') ? '&' : '?';

        return $meetingUrl.$separator.'config.prejoinPageEnabled=false&userInfo.displayName='.urlencode($displayName);
    }

    /**
     * Verificar si una reserva puede acceder a videollamada
     */
    public function canAccessVideoCall(Booking $booking, User $user): bool
    {
        // La reserva debe estar aceptada
        if ($booking->status !== 'aceptada') {
            return false;
        }

        // Debe tener URL asignada
        if (! $booking->meeting_url) {
            return false;
        }

        // El usuario debe ser profesor de la clase o estudiante de la reserva
        return $booking->student_id === $user->id || $booking->class->teacher_id === $user->id;
    }

    /**
     * Obtener estadísticas de uso de videollamadas
     */
    public function getVideoCallStats(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        $totalBookings = Booking::where('created_at', '>=', $startDate)
            ->where('status', 'aceptada')
            ->count();

        $videoCallBookings = Booking::where('created_at', '>=', $startDate)
            ->where('status', 'aceptada')
            ->whereNotNull('meeting_url')
            ->count();

        return [
            'total_bookings' => $totalBookings,
            'video_call_bookings' => $videoCallBookings,
            'percentage' => $totalBookings > 0 ? round(($videoCallBookings / $totalBookings) * 100, 2) : 0,
            'days_analyzed' => $days,
            'period' => $startDate->format('Y-m-d').' to '.now()->format('Y-m-d'),
        ];
    }
}
