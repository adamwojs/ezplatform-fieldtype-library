<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

final class ChoiceConverter implements Converter
{
    private const CHOICE_DELIMITER = ',';

    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue): void
    {
        if (!empty($value->data) && is_array($value->data)) {
            $storageFieldValue->dataText = implode(self::CHOICE_DELIMITER, $value->data);
        }

        $storageFieldValue->sortKeyString = $value->sortKey;
    }

    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue): void
    {
        if (!empty($value->dataText)) {
            $fieldValue->data = explode(self::CHOICE_DELIMITER, $value->dataText);
        }

        $fieldValue->sortKey = $value->sortKeyString;
    }

    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
        $selectionLengthValidator = $fieldDef->fieldTypeConstraints->validators['SelectionLengthValidator'];

        $storageDef->dataInt1 = $selectionLengthValidator['minSelectionLength'];
        $storageDef->dataInt2 = $selectionLengthValidator['maxSelectionLength'];
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        $fieldDef->fieldTypeConstraints->validators = [
            'SelectionLengthValidator' => [
                'minSelectionLength' => $storageDef->dataInt1,
                'maxSelectionLength' => $storageDef->dataInt2,
            ],
        ];
    }

    public function getIndexColumn(): string
    {
        return 'sort_key_string';
    }
}
