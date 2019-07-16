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
        $fieldSettings = $fieldDef->fieldTypeConstraints->fieldSettings;

        if (isset($fieldSettings['isMultiple'])) {
            $storageDef->dataInt1 = (int)$fieldSettings['isMultiple'];
        }
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        $fieldDef->fieldTypeConstraints->fieldSettings = [
            'isMultiple' => !empty($storageDef->dataInt1) ? (bool)$storageDef->dataInt1 : false,
        ];
    }

    public function getIndexColumn(): string
    {
        return 'sort_key_string';
    }
}
