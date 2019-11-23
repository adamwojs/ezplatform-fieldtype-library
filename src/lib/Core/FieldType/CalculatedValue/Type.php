<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\CalculatedValue;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

final class Type extends FieldType implements TranslationContainerInterface
{
    protected $settingsSchema = [
        'expression' => [
            'type' => 'string',
            'default' => null,
        ],
    ];

    public function getFieldTypeIdentifier(): string
    {
        return 'ezcalcval';
    }

    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        return '';
    }

    public function getEmptyValue(): Value
    {
        return new Value();
    }

    public function fromHash($hash): Value
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash(SPIValue $value): ?array
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return $value->getValue();
    }

    public function validateFieldSettings($fieldSettings): array
    {
        $validationErrors = [];

        foreach ($fieldSettings as $settingKey => $settingValue) {
            switch ($settingKey) {
                case 'expression':
                    break;
                default:
                    $validationErrors[] = new ValidationError(
                        "Setting '%setting%' is unknown",
                        null,
                        [
                            '%setting%' => $settingKey,
                        ],
                        "[$settingKey]"
                    );
            }
        }

        return $validationErrors;
    }

    protected function createValueFromInput($inputValue)
    {
        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value): void
    {
        // Value is self-contained and strong typed
        return;
    }

    public static function getTranslationMessages(): array
    {
        return [
            (new Message('ezcalcval.name', 'fieldtypes'))->setDesc('Calculated value'),
        ];
    }
}
