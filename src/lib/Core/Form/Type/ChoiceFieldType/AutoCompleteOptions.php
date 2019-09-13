<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;

final class AutoCompleteOptions
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
    private $choiceProvider;

    /** @var string */
    private $identifier;

    /**
     * @param string $identifier
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider $choiceProvider
     */
    public function __construct(string $identifier, ChoiceProvider $choiceProvider)
    {
        $this->identifier = $identifier;
        $this->choiceProvider = $choiceProvider;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getChoiceProvider(): ChoiceProvider
    {
        return $this->choiceProvider;
    }
}
