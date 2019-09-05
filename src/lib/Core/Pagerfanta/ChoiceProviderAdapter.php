<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Pagerfanta;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use Pagerfanta\Adapter\AdapterInterface;
use Traversable;

final class ChoiceProviderAdapter implements AdapterInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
    private $choiceProvider;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria */
    private $criteria;

    public function __construct(ChoiceProvider $choiceProvider, ChoiceCriteria $criteria)
    {
        $this->choiceProvider = $choiceProvider;
        $this->criteria = $criteria;
    }

    public function getNbResults(): int
    {
        return $this->choiceProvider->getChoiceList($this->criteria, 0, 0)->getTotalCount();
    }

    public function getSlice($offset, $length): Traversable
    {
        return $this->choiceProvider->getChoiceList($this->criteria, $offset, $length);
    }
}
