<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider as ChoiceProviderInterface;
use Symfony\Component\Intl\Languages;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getAllChoices(): array
    {
        $choices = [];
        foreach (Languages::getNames() as $code => $name) {
            $choices[] = new Choice($code, $name);
        }

        return $choices;
    }

    public function getChoicesForValues(iterable $values): array
    {
        $choices = [];
        foreach ($values as $value) {
            $choices[] = new Choice($value, Languages::getName($value));
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
