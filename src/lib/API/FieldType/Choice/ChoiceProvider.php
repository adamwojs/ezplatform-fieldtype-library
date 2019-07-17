<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice;

interface ChoiceProvider
{
    public function getAllChoices(): array;

    public function getChoicesForValues(iterable $values): array;

    public function getValueForChoice($choice): string;

    public function getLabelForChoice($choice): string;
}
