<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice;

use eZ\Publish\SPI\FieldType\Indexable;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\SPI\Search;
use eZ\Publish\SPI\Search\FieldType\FullTextField;
use eZ\Publish\SPI\Search\FieldType\IntegerField;
use eZ\Publish\SPI\Search\FieldType\MultipleStringField;
use eZ\Publish\SPI\Search\FieldType\StringField;

final class SearchField implements Indexable
{
    public function getIndexData(Field $field, FieldDefinition $fieldDefinition): array
    {
        $values = $field->value->data ?? [];

        return [
            new Search\Field(
                'selected_option_value',
                $values,
                new MultipleStringField()
            ),
            new Search\Field(
                'selected_option_count',
                count($values),
                new IntegerField()
            ),
            new Search\Field(
                'sort_value',
                implode('-', $values),
                new StringField()
            ),
            new Search\Field(
                'fulltext',
                $values,
                new FullTextField()
            ),
        ];
    }

    public function getIndexDefinition(): array
    {
        return [
            'selected_option_value' => new MultipleStringField(),
            'selected_option_count' => new IntegerField(),
            'sort_value' => new StringField(),
        ];
    }

    public function getDefaultMatchField(): string
    {
        return 'selected_option_value';
    }

    public function getDefaultSortField(): string
    {
        return 'sort_value';
    }
}
