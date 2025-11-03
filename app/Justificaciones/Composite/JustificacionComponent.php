<?php

namespace App\Justificaciones\Composite;

use Countable;
use IteratorAggregate;

interface JustificacionComponent extends IteratorAggregate, Countable, \JsonSerializable
{
    public function getType(): string;

    public function getLabel(): string;

    public function getPayload(): array;

    public function isComposite(): bool;

    public function toArray(): array;
}