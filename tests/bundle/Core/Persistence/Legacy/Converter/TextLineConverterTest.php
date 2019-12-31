<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Tests\Core\Persistence\Legacy\Converter;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter\TextLineConverter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use PHPUnit\Framework\TestCase;

final class TextLineConverterTest extends TestCase
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter\TextLineConverter */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new TextLineConverter();
    }

    public function testToStorageValue(): void
    {
        $value = new FieldValue();
        $value->data = "He's holding a thermal detonator!";
        $value->sortKey = "He's holding";

        $storageFieldValue = new StorageFieldValue();

        $this->converter->toStorageValue($value, $storageFieldValue);

        $this->assertEquals($value->data, $storageFieldValue->dataText);
        $this->assertEquals($value->sortKey, $storageFieldValue->sortKeyString);
        $this->assertEquals(0, $storageFieldValue->sortKeyInt);
    }

    public function testToFieldValue(): void
    {
        $storageFieldValue = new StorageFieldValue();
        $storageFieldValue->dataText = 'When 900 years old, you reach... Look as good, you will not.';
        $storageFieldValue->sortKeyString = 'When 900 years old, you reach...';
        $storageFieldValue->sortKeyInt = 0;
        $fieldValue = new FieldValue();

        $this->converter->toFieldValue($storageFieldValue, $fieldValue);

        $this->assertEquals($storageFieldValue->dataText, $fieldValue->data);
        $this->assertEquals($storageFieldValue->sortKeyString, $fieldValue->sortKey);
    }

    public function testGetIndexColumn(): void
    {
        $this->assertEquals('sort_key_string', $this->converter->getIndexColumn());
    }
}
