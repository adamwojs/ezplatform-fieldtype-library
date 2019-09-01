<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as AbstractChoiceType;

final class Type extends AbstractChoiceType
{
    public function getFieldTypeIdentifier(): string
    {
        return '<?= $identifier; ?>';
    }
}
