<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Timezone;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider as ChoiceProviderInterface;
use Symfony\Component\Intl\Timezones;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getAllChoices(): array
    {
        $choices = [];
        foreach (Timezones::getNames() as $id => $name) {
            $choices[] = new Choice($id, $name);
        }

        return $choices;
    }

    public function getChoicesForValues(iterable $values): array
    {
        $choices = [];
        foreach ($values as $value) {
            $choices[] = new Choice($value, Timezones::getName($value));
        }

        return $choices;
    }

    public function getValueForChoice($choice): string
    {
        return $choice->getId();
    }

    public function getLabelForChoice($choice): string
    {
        return $choice->getName();
    }
}
