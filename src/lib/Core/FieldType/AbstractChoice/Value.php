<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice;

use eZ\Publish\Core\FieldType\Value as BaseValue;

final class Value extends BaseValue
{
    /** @var array */
    private $selection;

    public function __construct(array $selection = [])
    {
        $this->selection = $selection;
    }

    public function hasSelection(): bool
    {
        return empty($this->selection);
    }

    public function getSelection(): array
    {
        return $this->selection;
    }

    public function getFirstSelection()
    {
        return $this->selection[0];
    }

    public function __toString(): string
    {
        return implode(',', $this->selection);
    }
}
