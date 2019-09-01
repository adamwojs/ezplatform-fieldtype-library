services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    <?= $field_type_definition ?>:
        class: <?= $field_type_definition_class ."\n" ?>
        arguments:
            $choiceProvider: '@<?= $field_type_definition ?>.choice_provider'
        tags:
            - { name: ezplatform.field_type, alias: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.choice_provider:
        class: <?= $choice_provider_class ?>

    <?= $field_type_definition ?>.converter:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter\ChoiceConverter
        tags:
            - { name: ezplatform.field_type.legacy_storage.converter, alias: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.indexable:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\SearchField
        tags:
            - { name: ezplatform.field_type.indexable, alias: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.form_mapper.value:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper\FieldValueFormMapper
        arguments:
            $choiceProvider: '@<?= $field_type_definition ?>.choice_provider'
        tags:
            - { name: ezplatform.field_type.form_mapper.value, fieldType: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.form_mapper.definition:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\FormMapper\FieldDefinitionFormMapper
        tags:
            - { name: ezplatform.field_type.form_mapper.definition, fieldType: <?= $field_type_identifier ?> }


