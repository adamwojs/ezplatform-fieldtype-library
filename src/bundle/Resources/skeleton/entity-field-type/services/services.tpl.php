services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    <?= $field_type_definition ?>:
        class: <?= $field_type_definition_class . "\n" ?>
        tags:
            - { name: ezplatform.field_type, alias: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.converter:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\Persistence\Legacy\Converter\EntityConverter
        tags:
            - { name: ezplatform.field_type.legacy_storage.converter, alias: <?= $field_type_identifier ?> }

    <?= $field_type_definition ?>.external_storage.gateway:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Gateway\StorageGateway

    <?= $field_type_definition ?>.external_storage:
        class: AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Gateway\Storage
        arguments:
            $gateway: '@<?= $field_type_definition ?>.external_storage.gateway'
            $entityClass: <?= $entity_full_name . "\n" ?>
        tags:
            - { name: ezplatform.field_type.external_storage_handler, alias: <?= $field_type_identifier ?> }
