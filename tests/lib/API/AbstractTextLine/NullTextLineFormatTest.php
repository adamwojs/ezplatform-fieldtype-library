<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\API\AbstractTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\NullTextLineFormat;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use PHPUnit\Framework\TestCase;

final class NullTextLineFormatTest extends TestCase
{
    private const EXAMPLE_VALUE = 'Lorem ipsum dolor...';

    public function testValidate(): void
    {
        $format = new NullTextLineFormat();

        $fieldDefinition = $this->createMock(FieldDefinition::class);

        $this->assertTrue($format->validate($fieldDefinition, self::EXAMPLE_VALUE));
    }
}
