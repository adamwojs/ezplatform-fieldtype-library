<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\Core\FieldType\AbstractTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\Type as AbstractTextLineType;

final class TypeStub extends AbstractTextLineType
{
    /** @var string */
    private $identifier;

    public function __construct(string $identifier, TextLineFormat $format)
    {
        parent::__construct($format);

        $this->identifier = $identifier;
    }

    public function getFieldTypeIdentifier(): string
    {
        return $this->identifier;
    }
}
