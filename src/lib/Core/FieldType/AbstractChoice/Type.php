<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;

abstract class Type extends FieldType
{
    protected $settingsSchema = [
        'isMultiple' => [
            'type' => 'bool',
            'default' => false,
        ],
    ];

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider */
    private $choiceProvider;

    public function __construct(ChoiceProvider $choiceProvider)
    {
        $this->choiceProvider = $choiceProvider;
    }

    public function getChoiceProvider(): ChoiceProvider
    {
        return $this->choiceProvider;
    }

    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        return '';
    }

    public function getEmptyValue(): Value
    {
        return new Value();
    }

    public function fromHash($hash): Value
    {
        if ($hash !== null) {
            return new Value($this->choiceProvider->getChoicesForValues($hash));
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

    public function validateFieldSettings($fieldSettings): array
    {
        $validationErrors = [];

        foreach ($fieldSettings as $settingKey => $settingValue) {
            switch ($settingKey) {
                case 'isMultiple':
                    if (!is_bool($settingValue)) {
                        $validationErrors[] = new ValidationError(
                            "FieldType '%fieldType%' expects setting '%setting%' to be of type '%type%'",
                            null,
                            [
                                '%fieldType%' => $this->getFieldTypeIdentifier(),
                                '%setting%' => $settingKey,
                                '%type%' => 'bool',
                            ],
                            "[$settingKey]"
                        );
                    }
                    break;
                default:
                    $validationErrors[] = new ValidationError(
                        "Setting '%setting%' is unknown",
                        null,
                        [
                            '%setting%' => $settingKey,
                        ],
                        "[$settingKey]"
                    );
            }
        }

        return $validationErrors;
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
