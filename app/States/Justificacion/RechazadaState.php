<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class RechazadaState extends JustificacionState
{
    public function name(): string
    {
        return Justificacion::STATUS_RECHAZADA;
    }
}