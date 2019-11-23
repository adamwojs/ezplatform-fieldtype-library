<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\Core\FieldType\MaskedTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\MaskedTextLine\Type;
use AdamWojs\EzPlatformFieldTypeLibrary\Tests\Core\FieldType\AbstractTextLine\TypeTest as AbstractTypeTest;

final class TypeTest extends AbstractTypeTest
{
    public function provideFieldTypeIdentifier(): string
    {
        return 'ezmaskedtextline';
    }

    public function provideValidFieldSettings(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'mask' => '#00-000',
                ],
            ],
        ];
    }

    public function provideInValidFieldSettings(): array
    {
        return [
            [
                [
                    'unknown_setting' => true,
                ],
            ],
        ];
    }

    protected function getFieldTypeUnderTest(): Type
    {
        return new Type($this->format);
    }

    protected function getSettingsSchemaExpectation(): array
    {
        return [
            'mask' => [
                'type' => 'string',
                'default' => null,
            ],
        ];
    }
}
