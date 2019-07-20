<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

final class PatternTextLineFormat implements TextLineFormat
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(FieldDefinition $fieldDefinition, string $text): bool
    {
        return preg_match($this->pattern, $text) === 1;
    }
}
