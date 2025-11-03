<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class CreadaState extends JustificacionState
{
    public function name(): string
    {
        return Justificacion::STATUS_CREADA;
    }

    public function allowedTransitions(): array
    {
        return [Justificacion::STATUS_ENVIADA];
    }

    public function send(): void
    {
        $this->transitionTo(Justificacion::STATUS_ENVIADA, action: 'enviar');
    }
}