<?php

namespace App\States\Justificacion\Exceptions;

use RuntimeException;

class InvalidStateTransition extends RuntimeException
{
    public static function make(string $state, string $action): self
    {
        return new self(sprintf('La acción "%s" no está permitida desde el estado "%s".', $action, $state));
    }
}