<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider as ChoiceProviderInterface;
use Symfony\Component\Intl\Languages;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getChoices(ChoiceCriteria $criteria): array
    {
        $choices = [];
        foreach (Languages::getNames() as $value => $label) {
            if ($criteria->getValues() !== null && !in_array($value, $criteria->getValues())) {
                continue;
            }

            $choices[] = new Choice($value, $label);
        }

        return $choices;
    }

    /**
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language\Choice $choice
     *
     * @return string
     */
    public function getValueForChoice($choice): string
    {
        return $choice->getLanguageCode();
    }

    /**
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language\Choice $choice
     *
     * @return string
     */
    public function getLabelForChoice($choice): string
    {
        return $choice->getName();
    }
}
