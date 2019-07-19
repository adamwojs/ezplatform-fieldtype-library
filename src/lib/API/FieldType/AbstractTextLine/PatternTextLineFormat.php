<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

final class PatternTextLineFormat implements TextLineFormat
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(string $text): bool
    {
        return preg_match($this->pattern, $text) === 1;
    }
}
