<?php

namespace App\Services\Notifications\Observers;

use App\Mail\ReporteProfesor;
use App\Models\Justificacion;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProfessorReportObserver implements JustificacionObserver
{
    public function update(?Justificacion $justificacion, array $context = []): void
    {
        if (($context['event'] ?? null) !== 'report_requested') {
            return;
        }

        $recipient = $context['recipient'] ?? null;
        $justificaciones = $context['justificaciones'] ?? Collection::make();
        $metadata = $context['metadata'] ?? [];

        if (!$recipient) {
            throw new \InvalidArgumentException('No se proporcionó un destinatario para el reporte.');
        }

        if (!$justificaciones instanceof Collection) {
            if ($justificaciones instanceof Arrayable) {
                $justificaciones = Collection::make($justificaciones->toArray());
            } else {
                $justificaciones = Collection::make($justificaciones);
            }
        }

        if ($justificaciones->isEmpty()) {
            throw new \InvalidArgumentException('No hay justificaciones para incluir en el reporte.');
        }

        try {
            Mail::to($recipient)->send(new ReporteProfesor($justificaciones, $metadata));
        } catch (\Throwable $exception) {
            Log::error('Fallo al enviar reporte a profesor: ' . $exception->getMessage());
            throw $exception;
        }
    }
}