<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\FormInterface;

final class FieldValueFormMapper implements FieldValueFormMapperInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider */
    private $choiceProvider;

    public function __construct(ChoiceProvider $choiceProvider)
    {
        $this->choiceProvider = $choiceProvider;
    }

    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data): void
    {
        $definition = $data->fieldDefinition;

        $fieldForm->add('value', ChoiceFieldType::class, [
            'required' => $definition->isRequired,
            'label' => $definition->getName(),
            'multiple' => $definition->fieldSettings['isMultiple'],
            'choice_provider' => $this->choiceProvider,
        ]);
    }
}
