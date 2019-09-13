<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

final class ChoiceList implements IteratorAggregate
{
    /** @var int */
    private $totalCount;

    /** @var mixed[] */
    private $choices;

    public function __construct(array $items = [], int $totalCount = 0)
    {
        $this->totalCount = $totalCount;
        $this->choices = $items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function toArray(): array
    {
        return $this->choices;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->choices);
    }
}
