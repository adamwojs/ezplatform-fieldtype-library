<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

final class Format implements TextLineFormat
{
    public function validate(FieldDefinition $fieldDefinition, string $text): bool
    {
        return true;
    }
}
