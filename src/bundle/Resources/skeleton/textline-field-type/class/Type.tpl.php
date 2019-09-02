<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\Type as AbstractTextLineType;

final class Type extends AbstractTextLineType
{
    public function getFieldTypeIdentifier(): string
    {
        return '<?= $identifier; ?>';
    }
}
