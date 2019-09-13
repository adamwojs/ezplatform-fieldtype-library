<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

interface ChoiceProvider
{
    public function getChoiceList(ChoiceCriteria $criteria, ?int $offset = null, ?int $limit = null): ChoiceList;

    public function getValueForChoice($choice): string;

    public function getLabelForChoice($choice): string;
}
