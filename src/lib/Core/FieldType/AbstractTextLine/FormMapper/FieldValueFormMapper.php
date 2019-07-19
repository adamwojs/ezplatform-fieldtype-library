<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\FormMapper;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\TextLineFieldType;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\FormInterface;

final class FieldValueFormMapper implements FieldValueFormMapperInterface
{
    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat */
    private $format;

    public function __construct(TextLineFormat $format)
    {
        $this->format = $format;
    }

    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data): void
    {
        $definition = $data->fieldDefinition;

        $fieldForm->add('value', TextLineFieldType::class, [
            'required' => $definition->isRequired,
            'label' => $definition->getName(),
        ]);
    }
}
