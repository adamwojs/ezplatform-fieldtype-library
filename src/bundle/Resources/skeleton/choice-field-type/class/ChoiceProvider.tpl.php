<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider as ChoiceProviderInterface;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList;

final class ChoiceProvider implements ChoiceProviderInterface
{
    public function getChoices(ChoiceCriteria $criteria, ?int $offset = null, ?int $limit = null): ChoiceList
    {
        return new ChoiceList();
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
