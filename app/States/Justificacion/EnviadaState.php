<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class EnviadaState extends JustificacionState
{
    public function name(): string
    {
        return Justificacion::STATUS_ENVIADA;
    }

    public function allowedTransitions(): array
    {
        return [
            Justificacion::STATUS_APROBADA,
            Justificacion::STATUS_RECHAZADA,
            Justificacion::STATUS_EXPIRADA,
        ];
    }

    public function approve(): void
    {
        $this->transitionTo(
            Justificacion::STATUS_APROBADA,
            ['rejection_reason' => null],
            'aprobar'
        );
    }

    public function reject(?string $reason = null): void
    {
        $this->transitionTo(
            Justificacion::STATUS_RECHAZADA,
            ['rejection_reason' => $reason],
            'rechazar'
        );
    }

    public function expire(): void
    {
        $this->transitionTo(
            Justificacion::STATUS_EXPIRADA,
            ['rejection_reason' => null],
            'expirar'
        );
    }
}