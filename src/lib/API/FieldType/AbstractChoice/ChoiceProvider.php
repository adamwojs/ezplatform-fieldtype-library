<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

interface ChoiceProvider
{
    public function getChoices(ChoiceCriteria $criteria): array;

    public function getValueForChoice($choice): string;

    public function getLabelForChoice($choice): string;
}
