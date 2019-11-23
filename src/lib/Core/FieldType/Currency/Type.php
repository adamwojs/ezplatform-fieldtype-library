<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Currency;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as AbstractChoiceType;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

final class Type extends AbstractChoiceType implements TranslationContainerInterface
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ezcurrency';
    }

    public static function getTranslationMessages(): array
    {
        return [
            (new Message('ezcurrency.name', 'fieldtypes'))->setDesc('Currency'),
        ];
    }
}
