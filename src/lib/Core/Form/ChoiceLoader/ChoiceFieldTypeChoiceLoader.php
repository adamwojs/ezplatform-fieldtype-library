<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\ChoiceLoader;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;

/**
 * @internal
 */
final class ChoiceFieldTypeChoiceLoader implements ChoiceLoaderInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
    private $choiceProvider;

    /** @var array */
    private $selection;

    public function __construct(ChoiceProvider $choiceProvider, array $selection = [])
    {
        $this->choiceProvider = $choiceProvider;
        $this->selection = $selection;
    }

    public function loadChoiceList($value = null): ChoiceListInterface
    {
        return new ArrayChoiceList($this->selection, $value);
    }

    public function loadChoicesForValues(array $values, $value = null): array
    {
        if (empty($values)) {
            return [];
        }

        return $this->choiceProvider->getChoiceList(
            new ChoiceCriteria($values)
        )->toArray();
    }

    public function loadValuesForChoices(array $choices, $value = null): array
    {
        $values = [];

        foreach ($choices as $key => $choice) {
            if ($choice !== null) {
                $values[$key] = $this->choiceProvider->getValueForChoice($choice);
            }
        }

        return $values;
    }

    public function setSelection(array $selection): void
    {
        $this->selection = $selection;
    }
}
