<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\MaskedTextLine\FormMapper;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractTextLine\TextLineFormat;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\MaskedTextLineFieldType;
use EzSystems\EzPlatformContentForms\Data\Content\FieldData;
use EzSystems\EzPlatformContentForms\FieldType\FieldValueFormMapperInterface;
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

        $fieldForm->add('value', MaskedTextLineFieldType::class, [
            'required' => $definition->isRequired,
            'label' => $definition->getName(),
            'mask' => $definition->fieldSettings['mask'],
        ]);
    }
}
