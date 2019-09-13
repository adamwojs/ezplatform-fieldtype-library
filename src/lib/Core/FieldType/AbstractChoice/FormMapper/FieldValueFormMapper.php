<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType\AutoCompleteOptions;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\FormInterface;

final class FieldValueFormMapper implements FieldValueFormMapperInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
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
            'multiple' => $this->isMultipleSelectionAllowed($definition),
            'auto_complete' => new AutoCompleteOptions(
                $definition->fieldTypeIdentifier,
                $this->choiceProvider,
            ),
        ]);
    }

    private function isMultipleSelectionAllowed(FieldDefinition $fieldDefinition): bool
    {
        $validator = $fieldDefinition->validatorConfiguration['SelectionLengthValidator'];

        return $validator['minSelectionLength'] !== 1
            && $validator['maxSelectionLength'] !== 1;
    }
}
