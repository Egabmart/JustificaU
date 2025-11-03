<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;
use App\Services\Notifications\JustificacionNotifier;
use App\States\Justificacion\Exceptions\InvalidStateTransition;

abstract class JustificacionState
{
    public function __construct(
        protected Justificacion $justificacion,
        protected JustificacionNotifier $notifier
    ) {
    }

    abstract public function name(): string;

    /**
     * @return string[]
     */
    public function allowedTransitions(): array
    {
        return [];
    }

    public function availableTransitions(): array
    {
        return $this->allowedTransitions();
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }

    public function send(): void
    {
        throw InvalidStateTransition::make($this->name(), 'enviar');
    }

    public function approve(): void
    {
        throw InvalidStateTransition::make($this->name(), 'aprobar');
    }

    public function reject(?string $reason = null): void
    {
        throw InvalidStateTransition::make($this->name(), 'rechazar');
    }

    public function expire(): void
    {
        throw InvalidStateTransition::make($this->name(), 'expirar');
    }

    protected function transitionTo(string $status, array $attributes = [], ?string $action = null): void
    {
        if (!$this->canTransitionTo($status)) {
            throw InvalidStateTransition::make($this->name(), $action ?? $status);
        }

        $previousStatus = $this->justificacion->status;

        $this->justificacion->fill(array_merge($attributes, [
            'status' => $status,
        ]));

        $dirty = $this->justificacion->isDirty();
        $statusChanged = $this->justificacion->isDirty('status');

        if (!$dirty) {
            return;
        }

        $this->justificacion->save();

        if ($statusChanged) {
            $this->notifier->notify($this->justificacion, [
                'event' => 'status_updated',
                'previous_status' => $previousStatus,
                'new_status' => $status,
            ]);
        }
    }

    public function __toString(): string
    {
        return $this->name();
    }
}