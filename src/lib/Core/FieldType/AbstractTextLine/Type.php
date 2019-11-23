<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;

abstract class Type extends FieldType
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat */
    private $format;

    public function __construct(TextLineFormat $format)
    {
        $this->format = $format;
    }

    protected static function checkValueType($value): void
    {
        if (!$value instanceof Value) {
            throw new InvalidArgumentType('$value', Value::class, $value);
        }
    }

    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        return (string)$value->getText();
    }

    public function getEmptyValue(): Value
    {
        return new Value();
    }

    public function getFormat(): TextLineFormat
    {
        return $this->format;
    }

    public function fromHash($hash): Value
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash(SPIValue $value): ?string
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return $value->getText();
    }

    public function isEmptyValue(SPIValue $value): bool
    {
        return $value->getText() === null || trim($value->getText()) === '';
    }

    public function isSearchable(): bool
    {
        return true;
    }

    public function validate(FieldDefinition $fieldDefinition, SPIValue $fieldValue): array
    {
        $validationErrors = [];

        if ($this->isEmptyValue($fieldValue)) {
            return $validationErrors;
        }

        if (!$this->format->validate($fieldDefinition, $fieldValue->getText())) {
            $validationErrors[] = new ValidationError(
                "The string doesn't match specified format.",
                null,
                [],
                'text'
            );
        }

        return $validationErrors;
    }

    protected function createValueFromInput($inputValue)
    {
        if (is_string($inputValue)) {
            $inputValue = new Value($inputValue);
        }

        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value): void
    {
        // Value is self-contained and strong typed
        return;
    }
}
