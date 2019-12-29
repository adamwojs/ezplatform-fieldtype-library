<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\Core\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value;
use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\SPI\FieldType\Tests\FieldTypeTest;
use eZ\Publish\SPI\FieldType\Value as SPIValue;

class TypeTest extends FieldTypeTest
{
    private const FIELD_TYPE_IDENTIFIER = 'custom_choice';

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider|\PHPUnit\Framework\MockObject\MockObject */
    private $provider;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ChoiceProvider::class);
    }

    public function provideInvalidInputForAcceptValue(): array
    {
        return [
            [
                0xFFFF,
                InvalidArgumentException::class,
            ],
            [
                $this->createMock(ValueObject::class),
                InvalidArgumentException::class,
            ],
        ];
    }

    public function provideValidInputForAcceptValue(): array
    {
        return [
            [
                null,
                new Value(),
            ],
            [
                new Value(['foo', 'bar', 'baz']),
                new Value(['foo', 'bar', 'baz']),
            ],
        ];
    }

    public function provideInputForToHash(): array
    {
        return [
            [
                new Value(),
                [],
            ],
            [
                new Value(['foo', 'bar', 'baz']),
                ['foo', 'bar', 'baz'],
            ],
        ];
    }

    /**
     * @dataProvider provideInputForToHash
     */
    public function testToHash($inputValue, $expectedResult): void
    {
        $this->provider->method('getValueForChoice')->willReturnArgument(0);

        parent::testToHash($inputValue, $expectedResult);
    }

    public function provideInputForFromHash(): array
    {
        return [
            [
                null,
                new Value(),
            ],
            [
                ['foo', 'bar', 'baz'],
                new Value(['foo', 'bar', 'baz']),
            ],
        ];
    }

    /**
     * @dataProvider provideInputForFromHash
     */
    public function testFromHash($inputHash, $expectedResult): void
    {
        if ($inputHash !== null) {
            $criteria = new ChoiceCriteria($inputHash);
            $choiceList = new ChoiceList($inputHash, count($inputHash));

            $this->provider
                ->method('getChoiceList')
                ->with($criteria)
                ->willReturn($choiceList);
        }

        parent::testFromHash($inputHash, $expectedResult);
    }

    public function provideDataForGetName(): array
    {
        return [
            [new Value(), [], 'eng-GB', ''],
            [new Value(['foo', 'bar', 'baz']), [], 'eng-GB', 'foo bar baz'],
        ];
    }

    /**
     * @dataProvider provideDataForGetName
     */
    public function testGetName(SPIValue $value, array $fieldSettings = [], string $languageCode = 'en_GB', string $expected)
    {
        $this->provider->method('getLabelForChoice')->willReturnArgument(0);

        parent::testGetName($value, $fieldSettings, $languageCode, $expected);
    }

    public function provideValidDataForValidate(): array
    {
        return [
        ];
    }

    public function provideInvalidDataForValidate(): array
    {
        return [
            'Non-existing choices' => [
                [],
                new Value(['unknown']),
                [
                    new ValidationError(
                        'Choice with index %index% does not exist.',
                        null,
                        [
                            '%index%' => 0,
                        ],
                        'selection'
                    ),
                ],
            ],
            'Selected less then SelectionLengthValidator[minSelectionLength] choices' => [
                [
                    'validatorConfiguration' => [
                        'SelectionLengthValidator' => [
                            'minSelectionLength' => 3,
                            'maxSelectionLength' => -1,
                        ],
                    ],
                ],
                new Value(['foo']),
                [
                    new ValidationError(
                        'At least %count% option(s) needs to be selected.',
                        null,
                        [
                            '%count%' => 3,
                        ],
                        'selection'
                    ),
                ],
            ],
            'Selected more then SelectionLengthValidator[maxSelectionLength] choices' => [
                [
                    'validatorConfiguration' => [
                        'SelectionLengthValidator' => [
                            'minSelectionLength' => 0,
                            'maxSelectionLength' => 2,
                        ],
                    ],
                ],
                new Value(['foo', 'bar', 'baz']),
                [
                    new ValidationError(
                        'No more then %count% options need to be selected.',
                        null,
                        [
                            '%count%' => 2,
                        ],
                        'selection'
                    ),
                ],
            ],
            'SelectionLengthValidator[minSelectionLength] === SelectionLengthValidator[maxSelectionLength]' => [
                [
                    'validatorConfiguration' => [
                        'SelectionLengthValidator' => [
                            'minSelectionLength' => 2,
                            'maxSelectionLength' => 2,
                        ],
                    ],
                ],
                new Value(['foo', 'bar', 'baz']),
                [
                    new ValidationError(
                        '%count% option(s) needs to be selected.',
                        null,
                        [
                            '%count%' => 2,
                        ],
                        'selection'
                    ),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideInvalidDataForValidate
     */
    public function testValidateInvalid($fieldDefinitionData, $value, $errors): void
    {
        $this->provider
            ->method('getChoiceList')
            ->with(new ChoiceCriteria($value->getSelection()))
            ->willReturnCallback(static function (ChoiceCriteria $criteria): ChoiceList {
                $choices = array_filter($criteria->getValues(), static function (string $value): bool {
                    return in_array($value, ['foo', 'bar', 'baz']);
                });

                return new ChoiceList($choices, count($choices));
            });

        $this->provider
            ->method('getValueForChoice')
            ->willReturnArgument(0);

        parent::testValidateInvalid($fieldDefinitionData, $value, $errors);
    }

    public function provideValidValidatorConfiguration(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'SelectionLengthValidator' => [
                        'minSelectionLength' => 1,
                        'maxSelectionLength' => 3,
                    ],
                ],
            ],
        ];
    }

    public function provideInvalidValidatorConfiguration(): array
    {
        return [
            'Non existing validator' => [
                [
                    'NonExistentValidator' => [],
                ],
            ],
            'Unknown SelectionLengthValidator parameter' => [
                [
                    'SelectionLengthValidator' => [
                        'unknownParameter' => 1,
                    ],
                ],
            ],
            'Invalid SelectionLengthValidator[minSelectionLength] parameter value type' => [
                [
                    'SelectionLengthValidator' => [
                        'minSelectionLength' => 'string',
                    ],
                ],
            ],
            'Invalid SelectionLengthValidator[maxSelectionLength] parameter value type' => [
                [
                    'SelectionLengthValidator' => [
                        'maxSelectionLength' => 'string',
                    ],
                ],
            ],
        ];
    }

    public function testGetChoiceProvider(): void
    {
        $this->assertEquals(
            $this->provider,
            $this->getFieldTypeUnderTest()->getChoiceProvider()
        );
    }

    public function testIsSearchable(): void
    {
        $this->assertTrue($this->getFieldTypeUnderTest()->isSearchable());
    }

    protected function provideFieldTypeIdentifier(): string
    {
        return self::FIELD_TYPE_IDENTIFIER;
    }

    protected function createFieldTypeUnderTest(): Type
    {
        return new TypeStub(self::FIELD_TYPE_IDENTIFIER, $this->provider);
    }

    protected function getValidatorConfigurationSchemaExpectation(): array
    {
        return [
            'SelectionLengthValidator' => [
                'minSelectionLength' => [
                    'type' => 'int',
                    'default' => 1,
                ],
                'maxSelectionLength' => [
                    'type' => 'int',
                    'default' => 1,
                ],
            ],
        ];
    }

    protected function getSettingsSchemaExpectation(): array
    {
        return [];
    }

    protected function getEmptyValueExpectation(): Value
    {
        return new Value();
    }
}
