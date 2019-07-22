<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\AutoCompleteChoiceType;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\FormInterface;

final class FieldValueFormMapper implements FieldValueFormMapperInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider */
    private $choiceProvider;

    /** @var string|null */
    private $choiceWidget;

    public function __construct(ChoiceProvider $choiceProvider, ?string $choiceWidget = null)
    {
        $this->choiceProvider = $choiceProvider;
        $this->choiceWidget = $choiceWidget ?? AutoCompleteChoiceType::class;
    }

    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data): void
    {
        $definition = $data->fieldDefinition;

        $fieldForm->add('value', ChoiceFieldType::class, [
            'required' => $definition->isRequired,
            'label' => $definition->getName(),
            'multiple' => $definition->fieldSettings['isMultiple'],
            'choice_provider' => $this->choiceProvider,
            'choice_widget' => $this->choiceWidget,
        ]);
    }
}
