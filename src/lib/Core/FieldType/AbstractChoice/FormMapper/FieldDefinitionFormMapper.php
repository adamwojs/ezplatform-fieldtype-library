<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper;

use EzSystems\EzPlatformAdminUi\Form\Data\FieldDefinitionData;
use EzSystems\EzPlatformAdminUi\FieldType\FieldDefinitionFormMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;

final class FieldDefinitionFormMapper implements FieldDefinitionFormMapperInterface
{
    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data): void
    {
        $isTranslation = $data->contentTypeData->languageCode !== $data->contentTypeData->mainLanguageCode;

        $fieldDefinitionForm->add('minSelectionLength', IntegerType::class, [
            'required' => false,
            'property_path' => 'validatorConfiguration[SelectionLengthValidator][minSelectionLength]',
            'disabled' => $isTranslation,
        ]);

        $fieldDefinitionForm->add('maxSelectionLength', IntegerType::class, [
            'required' => false,
            'property_path' => 'validatorConfiguration[SelectionLengthValidator][maxSelectionLength]',
            'disabled' => $isTranslation,
        ]);
    }
}
