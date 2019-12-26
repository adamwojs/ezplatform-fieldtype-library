<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\API\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProviderRegistry;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as ChoiceType;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\Core\FieldType\FieldTypeRegistry;
use eZ\Publish\Core\FieldType\Null\Type as NullType;
use eZ\Publish\SPI\FieldType\FieldType;
use PHPUnit\Framework\TestCase;

final class ChoiceProviderRegistryTest extends TestCase
{
    private const EXAMPLE_FIELD_TYPE_IDENTIFIER = 'choice';

    /** @var \eZ\Publish\Core\FieldType\FieldTypeRegistry|\PHPUnit\Framework\MockObject\MockObject */
    private $fieldTypeRegistry;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProviderRegistry */
    private $choiceProviderRegistry;

    protected function setUp(): void
    {
        $this->fieldTypeRegistry = $this->createMock(FieldTypeRegistry::class);
        $this->choiceProviderRegistry = new ChoiceProviderRegistry($this->fieldTypeRegistry);
    }

    public function testHasChoiceProviderReturnsTrue(): void
    {
        $this->assumeFieldTypeRegistryContainsFieldType(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER,
            $this->createMock(ChoiceType::class)
        );

        $hasChoiceProvider = $this->choiceProviderRegistry->hasChoiceProvider(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER
        );

        $this->assertTrue($hasChoiceProvider);
    }

    public function testHasChoiceProviderReturnsFalseForNonExistingFieldType(): void
    {
        $this->assumeFieldTypeRegistryNotContainsFieldType(self::EXAMPLE_FIELD_TYPE_IDENTIFIER);

        $hasChoiceProvider = $this->choiceProviderRegistry->hasChoiceProvider(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER
        );

        $this->assertFalse($hasChoiceProvider);
    }

    public function testHasChoiceProviderReturnsFalseForNonChoiceFieldType(): void
    {
        $this->assumeFieldTypeRegistryContainsFieldType(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER,
            $this->createMock(NullType::class)
        );

        $hasChoiceProvider = $this->choiceProviderRegistry->hasChoiceProvider(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER
        );

        $this->assertFalse($hasChoiceProvider);
    }

    public function testGetChoiceProvider(): void
    {
        $expectedChoiceProvider = $this->createMock(ChoiceProvider::class);

        $choiceFieldType = $this->createMock(ChoiceType::class);
        $choiceFieldType->method('getChoiceProvider')->willReturn($expectedChoiceProvider);

        $this->assumeFieldTypeRegistryContainsFieldType(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER,
            $choiceFieldType
        );

        $actualChoiceProvider = $this->choiceProviderRegistry->getChoiceProvider(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER
        );

        $this->assertEquals(
            $expectedChoiceProvider,
            $actualChoiceProvider
        );
    }

    public function testGetChoiceProviderThrowsNotFoundExceptionForNonExistingFieldType(): void
    {
        $this->expectException(NotFoundException::class);

        $this->assumeFieldTypeRegistryNotContainsFieldType(self::EXAMPLE_FIELD_TYPE_IDENTIFIER);
        $this->choiceProviderRegistry->getChoiceProvider(self::EXAMPLE_FIELD_TYPE_IDENTIFIER);
    }

    public function testGetChoiceProviderThrowsNotFoundExceptionForNonChoiceFieldType(): void
    {
        $this->expectException(NotFoundException::class);

        $this->assumeFieldTypeRegistryContainsFieldType(
            self::EXAMPLE_FIELD_TYPE_IDENTIFIER,
            $this->createMock(NullType::class)
        );

        $this->choiceProviderRegistry->getChoiceProvider(self::EXAMPLE_FIELD_TYPE_IDENTIFIER);
    }

    private function assumeFieldTypeRegistryContainsFieldType(
        string $fieldTypeIdentifier,
        FieldType $fieldType
    ): void {
        $this->fieldTypeRegistry
            ->method('hasFieldType')
            ->with($fieldTypeIdentifier)
            ->willReturn(true);

        $this->fieldTypeRegistry
            ->method('getFieldType')
            ->with($fieldTypeIdentifier)
            ->willReturn($fieldType);
    }

    private function assumeFieldTypeRegistryNotContainsFieldType(string $fieldTypeIdentifier): void
    {
        $this->fieldTypeRegistry
            ->method('hasFieldType')
            ->with($fieldTypeIdentifier)
            ->willReturn(false);
    }
}
