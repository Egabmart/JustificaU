<?php

namespace App\Services\Notifications\Observers;

use App\Models\Justificacion;

interface JustificacionObserver
{
    /**
     * Handle the notification for a Justificacion change.
     *
     * @param  Justificacion|null  $justificacion  The related justification, if available.
     * @param  array  $context  Extra information about the event being dispatched.
     */
    public function update(?Justificacion $justificacion, array $context = []): void;
}