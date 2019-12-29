<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\Core\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as AbstractChoiceType;

final class TypeStub extends AbstractChoiceType
{
    /** @var string */
    private $identifier;

    public function __construct(string $identifier, ChoiceProvider $provider)
    {
        parent::__construct($provider);

        $this->identifier = $identifier;
    }

    public function getFieldTypeIdentifier(): string
    {
        return $this->identifier;
    }
}
