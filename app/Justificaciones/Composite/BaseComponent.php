<?php

namespace App\Justificaciones\Composite;

use ArrayIterator;
use Traversable;

abstract class BaseComponent implements JustificacionComponent
{
    protected const TYPE = 'component';

    public function __construct(
        protected string $label,
        protected array $payload = []
    ) {
    }

    public function getType(): string
    {
        return static::TYPE;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function isComposite(): bool
    {
        return false;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator([]);
    }

    public function count(): int
    {
        return 1;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'label' => $this->getLabel(),
            'payload' => $this->getPayload(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}