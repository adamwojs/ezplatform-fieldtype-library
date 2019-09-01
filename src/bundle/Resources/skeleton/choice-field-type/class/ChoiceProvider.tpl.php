<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider as ChoiceProviderInterface;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getChoices(ChoiceCriteria $criteria): array
    {
        return [];
    }

    public function getValueForChoice($choice): string
    {
        return '';
    }

    public function getLabelForChoice($choice): string
    {
        return '';
    }
}
