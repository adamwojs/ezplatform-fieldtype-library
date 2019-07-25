<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\MaskedTextLine;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\Type as AbstractType;
use eZ\Publish\Core\FieldType\ValidationError;

final class Type extends AbstractType
{
    protected $settingsSchema = [
        'mask' => [
            'type' => 'string',
            'default' => null,
        ],
    ];

    public function getFieldTypeIdentifier(): string
    {
        return 'ezmaskedtextline';
    }

    public function validateFieldSettings($fieldSettings): array
    {
        $validationErrors = [];

        foreach ($fieldSettings as $settingKey => $settingValue) {
            switch ($settingKey) {
                case 'mask':
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
}
