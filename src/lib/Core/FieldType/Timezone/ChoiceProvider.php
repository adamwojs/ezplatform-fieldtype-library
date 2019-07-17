<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Timezone;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider as ChoiceProviderInterface;
use Symfony\Component\Intl\Timezones;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getChoices(ChoiceCriteria $criteria): array
    {
        $choices = [];
        foreach (Timezones::getNames() as $value => $label) {
            if ($criteria->getValues() !== null && !in_array($value, $criteria->getValues())) {
                continue;
            }
            $choices[] = new Choice($value, $label);
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
