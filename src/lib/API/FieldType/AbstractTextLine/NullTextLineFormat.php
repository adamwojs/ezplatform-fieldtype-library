<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

final class NullTextLineFormat implements TextLineFormat
{
    public function validate(string $text): bool
    {
        return true;
    }
}
