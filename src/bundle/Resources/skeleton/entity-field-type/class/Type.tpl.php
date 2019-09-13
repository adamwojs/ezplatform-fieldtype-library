<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Type as AbstractEntityType;
use <?= $entity_full_name; ?>;

final class Type extends AbstractEntityType
{
    public function getFieldTypeIdentifier(): string
    {
        return '<?= $identifier; ?>';
    }

    protected function getValueClass(): string
    {
        return <?= $entity_short_name; ?>::class;
    }
}
