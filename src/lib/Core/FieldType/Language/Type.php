<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as AbstractChoiceType;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

final class Type extends AbstractChoiceType implements TranslationContainerInterface
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ezlanguage';
    }

    public static function getTranslationMessages(): array
    {
        return [
            (new Message('ezlanguage.name', 'fieldtypes'))->setDesc('Language'),
        ];
    }
}
