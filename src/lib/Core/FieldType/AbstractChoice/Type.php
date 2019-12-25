<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;

abstract class Type extends FieldType
{
    protected $validatorConfigurationSchema = [
        'SelectionLengthValidator' => [
            'minSelectionLength' => [
                'type' => 'int',
                'default' => 1,
            ],
            'maxSelectionLength' => [
                'type' => 'int',
                'default' => 1,
            ],
        ],
    ];

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
    private $choiceProvider;

    public function __construct(ChoiceProvider $choiceProvider)
    {
        $this->choiceProvider = $choiceProvider;
    }

    public function getChoiceProvider(): ChoiceProvider
    {
        return $this->choiceProvider;
    }

    /**
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value $value
     *
     * @return string
     */
    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        if (!$value->hasSelection()) {
            return '';
        }

        $labels = [];
        foreach ($value->getSelection() as $choice) {
            $labels[] = $this->choiceProvider->getLabelForChoice($choice);
        }

        return implode(' ', $labels);
    }

    public function getEmptyValue(): Value
    {
        return new Value();
    }

    public function fromHash($hash): Value
    {
        if ($hash !== null) {
            return new Value($this->choiceProvider->getChoiceList(new ChoiceCriteria($hash))->toArray());
        }

        return $this->getEmptyValue();
    }

    /**
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value $value
     *
     * @return array
     */
    public function toHash(SPIValue $value): array
    {
        return array_map(function ($choice) {
            return $this->choiceProvider->getValueForChoice($choice);
        }, $value->getSelection());
    }

    /**
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value $value
     *
     * @return array
     */
    public function validate(FieldDefinition $fieldDefinition, SPIValue $value): array
    {
        $validationErrors = [];

        if ($this->isEmptyValue($value)) {
            return $validationErrors;
        }

        $validatorConfiguration = $fieldDefinition->getValidatorConfiguration();
        $selectionLengthConstraint = $validatorConfiguration['SelectionLengthValidator'] ?? [];

        $minSelectionLength = $selectionLengthConstraint['minSelectionLength'] ?? null;
        $maxSelectionLength = $selectionLengthConstraint['maxSelectionLength'] ?? null;

        if ($minSelectionLength > 0 || $maxSelectionLength > 0) {
            $selectionLength = count($value->getSelection());

            if ($minSelectionLength === $maxSelectionLength && $selectionLength !== $minSelectionLength) {
                $validationErrors[] = new ValidationError(
                    '%count% option(s) needs to be selected.',
                    null,
                    [
                        '%count%' => $minSelectionLength,
                    ],
                    'selection'
                );
            } elseif ($minSelectionLength > 0 && count($value->getSelection()) < $minSelectionLength) {
                $validationErrors[] = new ValidationError(
                    'At least %count% option(s) needs to be selected.',
                    null,
                    [
                        '%count%' => $minSelectionLength,
                    ],
                    'selection'
                );
            } elseif ($maxSelectionLength > 0 && count($value->getSelection()) > $maxSelectionLength) {
                $validationErrors[] = new ValidationError(
                    'No more then %count% options need to be selected.',
                    null,
                    [
                        '%count%' => $maxSelectionLength,
                    ],
                    'selection'
                );
            }
        }

        $selectedChoices = $value->getSelection();
        $availableChoices = $this->choiceProvider->getChoiceList(
            new ChoiceCriteria(array_map([$this->choiceProvider, 'getValueForChoice'], $selectedChoices))
        )->toArray();

        if (count($selectedChoices) !== count($availableChoices)) {
            foreach ($selectedChoices as $index => $choice) {
                if (!in_array($choice, $availableChoices)) {
                    $validationErrors[] = new ValidationError(
                        'Choice with index %index% does not exist.',
                        null,
                        [
                            '%index%' => $index,
                        ],
                        'selection'
                    );
                }
            }
        }

        return $validationErrors;
    }

    public function validateValidatorConfiguration($validatorConfiguration): array
    {
        $validationErrors = [];

        foreach ($validatorConfiguration as $validatorIdentifier => $constraints) {
            if ($validatorIdentifier !== 'SelectionLengthValidator') {
                $validationErrors[] = new ValidationError(
                    "Validator '%validator%' is unknown",
                    null,
                    [
                        '%validator%' => $validatorIdentifier,
                    ],
                    "[$validatorIdentifier]"
                );

                continue;
            }

            foreach ($constraints as $name => $value) {
                switch ($name) {
                    case 'minSelectionLength':
                    case 'maxSelectionLength':
                        if ($value !== null && !is_int($value)) {
                            $validationErrors[] = new ValidationError(
                                "Validator parameter '%parameter%' value must be of integer type",
                                null,
                                [
                                    '%parameter%' => $name,
                                ],
                                "[$validatorIdentifier][$name]"
                            );
                        }
                        break;
                    default:
                        $validationErrors[] = new ValidationError(
                            "Validator parameter '%parameter%' is unknown",
                            null,
                            [
                                '%parameter%' => $name,
                            ],
                            "[$validatorIdentifier][$name]"
                        );
                }
            }
        }

        return $validationErrors;
    }

    public function isSearchable(): bool
    {
        return true;
    }

    protected function createValueFromInput($inputValue)
    {
        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value): void
    {
        // Value is self-contained and strong typed
        return;
    }

    protected static function checkValueType($value): void
    {
        if (!($value instanceof Value)) {
            throw new InvalidArgumentType('$value', Value::class, $value);
        }
    }
}
