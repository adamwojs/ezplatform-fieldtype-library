<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

final class MaskedTextLineFormat implements TextLineFormat
{
    private const MASK_ESCAPE_SEQ = '\\';
    private const MASK_PLACEHOLDERS = [
        '0' => '([0-9])',
        '9' => '([0-9]?)',
        'L' => '([A-Za-z])',
        '?' => '([A-Za-z]?)',
        'A' => '([A-Za-z0-9])',
        'a' => '([A-Za-z0-9]?)',
        'C' => '([A-Za-z0-9_])',
        'c' => '([A-Za-z0-9_]?)',
        'X' => '([0-9A-Fa-f]])',
        'x' => '([0-9A-Fa-f]]?)',
    ];

    public function validate(FieldDefinition $fieldDefinition, string $text): bool
    {
        $mask = (string)$fieldDefinition->fieldSettings['mask'];

        if ($mask === '') {
            return true;
        }

        return preg_match($this->createPatternFromMask($mask), $text) === 1;
    }

    public function createPatternFromMask(string $mask): string
    {
        $pattern = [];
        $escaped = false;

        for ($i = 0; $i < mb_strlen($mask); ++$i) {
            $char = $mask[$i];

            if ($escaped) {
                $pattern[] = preg_quote($char);
                $escaped = false;
            } elseif ($char === self::MASK_ESCAPE_SEQ) {
                $escaped = true;
            } elseif (isset(self::MASK_PLACEHOLDERS[$char])) {
                $pattern[] = self::MASK_PLACEHOLDERS[$char];
            } else {
                $pattern[] = preg_quote($char);
            }
        }

        return '/^' . implode('', $pattern) . '$/';
    }
}
