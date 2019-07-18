<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider\Choice;
use Webmozart\Assert\Assert;

final class InMemoryChoiceProvider implements ChoiceProvider
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider\Choice[] */
    private $choices;

    public function __construct(array $choices)
    {
        $this->choices = [];
        foreach ($choices as $value => $label) {
            $this->choices[$value] = new Choice((string)$value, $label);
        }
    }

    public function getChoices(ChoiceCriteria $criteria): array
    {
        if (!$criteria->hasValues()) {
            return $this->choices;
        }

        return array_filter($this->choices, function ($value) use ($criteria) {
            return in_array($value, $criteria->getValues());
        });
    }

    public function getValueForChoice($choice): string
    {
        Assert::isInstanceOf($choice, Choice::class);

        return $choice->getValue();
    }

    public function getLabelForChoice($choice): string
    {
        Assert::isInstanceOf($choice, Choice::class);

        return $choice->getLabel();
    }
}
