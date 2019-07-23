<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity;

interface Value
{
    public function setFieldId(int $fieldId): void;

    public function setVersionNo(int $versionNo): void;
}
