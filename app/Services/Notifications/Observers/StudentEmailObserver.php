<?php

namespace App\Services\Notifications\Observers;

use App\Mail\JustificacionAprobada;
use App\Mail\JustificacionRechazada;
use App\Models\Justificacion;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class StudentEmailObserver implements JustificacionObserver
{
    public function update(?Justificacion $justificacion, array $context = []): void
    {
        if (($context['event'] ?? null) !== 'status_updated') {
            return;
        }

        if (!$justificacion) {
            return;
        }

        $previous = $context['previous_status'] ?? null;
        $current = $context['new_status'] ?? null;

        if ($previous === $current) {
            return;
        }

        $justificacion->loadMissing('user');

        if (!$justificacion->relationLoaded('user') || !$justificacion->user) {
            return;
        }

        if ($previous !== Justificacion::STATUS_APROBADA && $current === Justificacion::STATUS_APROBADA) {
            $this->sendMail($justificacion, new JustificacionAprobada($justificacion));
        }

        if ($previous === Justificacion::STATUS_ENVIADA && $current === Justificacion::STATUS_RECHAZADA) {
            $this->sendMail($justificacion, new JustificacionRechazada($justificacion));
        }
    }
private function sendMail(Justificacion $justificacion, Mailable $mailable): void
    {
        try {
            Mail::to($justificacion->user->email)->send($mailable);
        } catch (TransportExceptionInterface $exception) {
            Log::error('No se pudo enviar la notificación por correo al estudiante.', [
                'justificacion_id' => $justificacion->getKey(),
                'correo' => $justificacion->user->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}