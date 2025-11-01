<?php

namespace App\Services\Notifications\Observers;

use App\Mail\JustificacionAprobada;
use App\Mail\JustificacionRechazada;
use App\Models\Justificacion;
use Illuminate\Support\Facades\Mail;

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

        if ($previous !== 'Aprobada' && $current === 'Aprobada') {
            Mail::to($justificacion->user->email)->send(new JustificacionAprobada($justificacion));
        }

        if ($previous === 'Pendiente' && $current === 'Rechazada') {
            Mail::to($justificacion->user->email)->send(new JustificacionRechazada($justificacion));
        }
    }
}