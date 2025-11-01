<?php

namespace App\Services\Notifications;

use App\Models\Justificacion;
use App\Services\Notifications\Observers\JustificacionObserver;

class JustificacionNotifier
{
    /**
     * @var array<string, JustificacionObserver>
     */
    private array $observers = [];

    public function __construct(iterable $observers = [])
    {
        foreach ($observers as $observer) {
            if ($observer instanceof JustificacionObserver) {
                $this->attach($observer);
            }
        }
    }

    public function attach(JustificacionObserver $observer): void
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }

    public function detach(JustificacionObserver $observer): void
    {
        $hash = spl_object_hash($observer);

        if (isset($this->observers[$hash])) {
            unset($this->observers[$hash]);
        }
    }

    public function notify(?Justificacion $justificacion, array $context = []): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($justificacion, $context);
        }
    }
}