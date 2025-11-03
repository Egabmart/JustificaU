<?php

namespace App\Justificaciones\Composite;

use ArrayIterator;
use Traversable;

class JustificacionComposite extends BaseComponent
{
    protected const TYPE = 'composite';

    /** @var JustificacionComponent[] */
    protected array $children = [];

    public function add(JustificacionComponent $component): static
    {
        $this->children[] = $component;

        return $this;
    }

    public function remove(JustificacionComponent $component): static
    {
        $this->children = array_values(array_filter(
            $this->children,
            static fn (JustificacionComponent $candidate): bool => $candidate !== $component
        ));

        return $this;
    }

    public function clear(): static
    {
        $this->children = [];

        return $this;
    }

    public function isComposite(): bool
    {
        return true;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->children);
    }

    public function count(): int
    {
        return array_reduce(
            $this->children,
            static fn (int $carry, JustificacionComponent $component): int => $carry + $component->count(),
            0
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'label' => $this->getLabel(),
            'payload' => $this->getPayload(),
            'children' => array_map(
                static fn (JustificacionComponent $component): array => $component->toArray(),
                $this->children
            ),
        ];
    }
}