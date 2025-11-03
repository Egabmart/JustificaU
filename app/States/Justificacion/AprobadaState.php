<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class AprobadaState extends JustificacionState
{
    public function name(): string
    {
        return Justificacion::STATUS_APROBADA;
    }
}