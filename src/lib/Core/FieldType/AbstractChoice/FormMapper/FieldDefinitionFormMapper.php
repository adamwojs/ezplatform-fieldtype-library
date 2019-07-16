<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper;

use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;

final class FieldDefinitionFormMapper implements FieldDefinitionFormMapperInterface
{
    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data): void
    {
        $isTranslation = $data->contentTypeData->languageCode !== $data->contentTypeData->mainLanguageCode;

        $fieldDefinitionForm->add('isMultiple', CheckboxType::class, [
            'required' => false,
            'property_path' => 'fieldSettings[isMultiple]',
            'disabled' => $isTranslation,
        ]);
    }
}
