<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

final class ChoiceCriteria
{
    /** @var array|null */
    private $values;

    /** @var string|null */
    private $searchTerm;

    public function __construct(?array $values = null)
    {
        $this->values = $values;
    }

    public function getValues(): ?array
    {
        return $this->values;
    }

    public function hasValues(): bool
    {
        return !empty($this->values);
    }

    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    public static function withSearchTerm(?string $searchTerm): self
    {
        $criteria = new self();
        $criteria->searchTerm = $searchTerm;

        return $criteria;
    }

    public static function withValues(array $values): self
    {
        $criteria = new self();
        $criteria->values = $values;

        return $criteria;
    }
}
