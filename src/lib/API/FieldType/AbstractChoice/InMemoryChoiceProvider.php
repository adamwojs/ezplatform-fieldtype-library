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

    public function getChoiceList(ChoiceCriteria $criteria, ?int $offset = null, ?int $limit = null): ChoiceList
    {
        $choices = $this->filterChoices($criteria);
        $totalCount = count($choices);

        if ($offset !== null) {
            $choices = array_slice($choices, $offset, $limit);
        }

        return new ChoiceList($choices, $totalCount);
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

    private function filterChoices(ChoiceCriteria $criteria): array
    {
        if (!$criteria->hasValues() && $criteria->getSearchTerm() === null) {
            return $this->choices;
        }

        $choices = [];

        $pattern = $this->getSearchTermPattern($criteria);
        foreach ($this->choices as $choice) {
            if ($this->isMatchCriteria($criteria, $choice, $pattern)) {
                $choices[] = $choice;
            }
        }

        return $choices;
    }

    private function isMatchCriteria(ChoiceCriteria $criteria, Choice $choice, ?string $pattern): bool
    {
        if ($criteria->hasValues() && !in_array($choice->getValue(), $criteria->getValues())) {
            return false;
        }

        if ($pattern !== null && preg_match($pattern, $choice->getLabel()) === 0) {
            return false;
        }

        return true;
    }

    private function getSearchTermPattern(ChoiceCriteria $criteria): ?string
    {
        if ($criteria->getSearchTerm() !== null) {
            return '/^.*' . preg_quote($criteria->getSearchTerm()) . '.*/i';
        }

        return null;
    }
}
