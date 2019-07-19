<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

interface TextLineFormat
{
    public function validate(string $text): bool;
}
