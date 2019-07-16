<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice;

interface ChoiceProvider
{
    public function getChoices(): array;
}
