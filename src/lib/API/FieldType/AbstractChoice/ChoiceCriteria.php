<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

final class ChoiceCriteria
{
    /** @var array|null */
    private $values;

    public function __construct(?array $values = null)
    {
        return $this->values = $values;
    }

    public function getValues(): ?array
    {
        return $this->values;
    }

    public function hasValues(): bool
    {
        return !empty($this->values);
    }
}
