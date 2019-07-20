<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Currency;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider;
use Symfony\Component\Intl\Currencies;

final class ChoiceProviderFactory
{
    public function create(): ChoiceProvider
    {
        return new InMemoryChoiceProvider(Currencies::getNames());
    }
}
