<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

interface TextLineFormat
{
    public function validate(FieldDefinition $fieldDefinition, string $text): bool;
}
