<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider;
use Symfony\Component\Intl\Languages;

final class ChoiceProviderFactory
{
    public function create(): ChoiceProvider
    {
        return new InMemoryChoiceProvider(Languages::getNames());
    }
}
