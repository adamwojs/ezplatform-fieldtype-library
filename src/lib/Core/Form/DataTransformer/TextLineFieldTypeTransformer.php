<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\Value;
use Symfony\Component\Form\DataTransformerInterface;

final class TextLineFieldTypeTransformer implements DataTransformerInterface
{
    public function transform($value): ?array
    {
        if (empty($value)) {
            return null;
        }

        return [
            'text' => $value,
        ];
    }

    public function reverseTransform($value): ?Value
    {
        if (empty($value)) {
            return null;
        }

        return new Value($value['text']);
    }
}
