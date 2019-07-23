<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter as ConverterInterface;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

final class EntityConverter implements ConverterInterface
{
    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue): void
    {
    }

    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue): void
    {
    }

    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
        // TODO: Implement \eZ\Publish\Core\FieldType\GenericEntity\Converter::toStorageFieldDefinition
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        // TODO: Implement \eZ\Publish\Core\FieldType\GenericEntity\Converter::toFieldDefinition
    }

    public function getIndexColumn()
    {
        return false;
    }
}
