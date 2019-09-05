<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\REST\Value;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList as APIChoiceList;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use ArrayIterator;
use EzSystems\EzPlatformRest\Value as RestValue;
use Traversable;

final class ChoiceList extends RestValue implements \IteratorAggregate
{
    /** @var int */
    private $count = 0;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\Core\REST\Value\ChoiceListItem[] */
    private $items = [];

    public function __construct(array $items, int $count)
    {
        $this->items = $items;
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public static function createFromAPI(ChoiceProvider $provider, APIChoiceList $choices): self
    {
        $items = [];
        foreach ($choices as $choice) {
            $items[] = new ChoiceListItem(
                $provider->getValueForChoice($choice),
                $provider->getLabelForChoice($choice)
            );
        }

        return new self($items, $choices->getTotalCount());
    }
}
