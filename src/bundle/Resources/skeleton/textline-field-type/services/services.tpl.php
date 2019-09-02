services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    <?= $field_type_definition; ?>:
        class: <?= $field_type_definition_class . "\n"; ?>
        arguments:
            $format: '@<?= $field_type_definition; ?>.format'
        tags:
            - { name: ezplatform.field_type, alias: <?= $field_type_identifier; ?> }

    <?= $field_type_definition; ?>.format:
        class: <?= $format_class . "\n"; ?>

    <?= $field_type_definition; ?>.converter:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter\TextLineConverter
        tags:
            - { name: ezplatform.field_type.legacy_storage.converter, alias: <?= $field_type_identifier; ?> }

    <?= $field_type_definition; ?>.indexable:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\SearchField
        tags:
            - { name: ezplatform.field_type.indexable, alias: <?= $field_type_identifier; ?> }

    <?= $field_type_definition; ?>.form_mapper.value:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractTextLine\FormMapper\FieldValueFormMapper
        arguments:
            $format: '@<?= $field_type_definition; ?>.format'
        tags:
            - { name: ezplatform.field_type.form_mapper.value, fieldType: <?= $field_type_identifier; ?> }

