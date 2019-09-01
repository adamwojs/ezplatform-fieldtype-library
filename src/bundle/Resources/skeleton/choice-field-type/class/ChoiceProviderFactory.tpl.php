<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider;

final class ChoiceProviderFactory
{
    public function create(): ChoiceProvider
    {
        return new InMemoryChoiceProvider([
            'Foo' => 'foo',
            'Bar' => 'bar',
            'Baz' => 'baz',
        ]);
    }
}
