<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

interface ChoiceProvider
{
    /**
     * Returns the list of choices available in choice field type.
     *
     * Result list SHOULD contain only items matching given criteria.
     *
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria $criteria
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList
     */
    public function getChoiceList(ChoiceCriteria $criteria, ?int $offset = null, ?int $limit = null): ChoiceList;

    /**
     * Generates string used as value of given choice list item.
     *
     * @param mixed $choice
     *
     * @return string
     */
    public function getValueForChoice($choice): string;

    /**
     * Generates string used as value of given choice list item.
     *
     * @param mixed $choice
     *
     * @return string
     */
    public function getLabelForChoice($choice): string;
}
