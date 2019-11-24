<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\API\AbstractTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\PatternTextLineFormat;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use PHPUnit\Framework\TestCase;

final class PatternTextLineFormatTest extends TestCase
{
    private const EXAMPLE_PATTERN = '/^[0-9]+$/';

    private const EXAMPLE_VALID_VALUE = '123';
    private const EXAMPLE_INVALID_VALUE = 'ABC';

    public function testValidate(): void
    {
        $fieldDefinition = $this->createMock(FieldDefinition::class);

        $format = new PatternTextLineFormat(self::EXAMPLE_PATTERN);

        $this->assertTrue($format->validate($fieldDefinition, self::EXAMPLE_VALID_VALUE));
        $this->assertFalse($format->validate($fieldDefinition, self::EXAMPLE_INVALID_VALUE));
    }
}
