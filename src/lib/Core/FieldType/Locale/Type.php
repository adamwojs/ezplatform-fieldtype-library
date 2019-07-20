<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Locale;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as AbstractChoiceType;

final class Type extends AbstractChoiceType
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ezlocale';
    }
}
