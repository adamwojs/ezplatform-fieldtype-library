<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value;
use Symfony\Component\Form\DataTransformerInterface;

final class ChoiceFieldTypeTransformer implements DataTransformerInterface
{
    /** @var bool */
    private $isMultiple = false;

    public function __construct(bool $isMultiple = false)
    {
        $this->isMultiple = $isMultiple;
    }

    public function transform($value): ?array
    {
        if (empty($value) || !$value->hasSelection()) {
            return null;
        }

        return [
            'selection' => $this->isMultiple ? $value->getSelection() : $value->getFirstSelection(),
        ];
    }

    public function reverseTransform($value): ?Value
    {
        if (empty($value)) {
            return null;
        }

        return new Value($this->isMultiple ? $value['selection'] : [$value['selection']]);
    }
}
