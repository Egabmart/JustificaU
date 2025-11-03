<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class ExpiradaState extends JustificacionState
{
    public function name(): string
    {
        return Justificacion::STATUS_EXPIRADA;
    }
}