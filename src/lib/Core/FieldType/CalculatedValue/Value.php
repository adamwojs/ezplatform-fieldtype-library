<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\CalculatedValue;

use eZ\Publish\Core\FieldType\Value as BaseValue;

final class Value extends BaseValue
{
    /** @var float|null */
    private $value;

    public function __construct(?float $value = null)
    {
        $this->value = $value;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return '';
    }
}
