<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Type as ChoiceType;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\FieldType\FieldTypeRegistry;

final class ChoiceProviderRegistry
{
    /** @var \eZ\Publish\Core\FieldType\FieldTypeRegistry */
    private $fieldTypeRegistry;

    public function __construct(FieldTypeRegistry $fieldTypeRegistry)
    {
        $this->fieldTypeRegistry = $fieldTypeRegistry;
    }

    public function hasChoiceProvider(string $identifier): bool
    {
        return $this->findChoiceProvider($identifier) !== null;
    }

    public function getChoiceProvider(string $identifier): ChoiceProvider
    {
        if (null !== ($provider = $this->findChoiceProvider($identifier))) {
            return $provider;
        }

        throw new NotFoundException('ChoiceProvider', $identifier);
    }

    private function findChoiceProvider(string $identifier): ?ChoiceProvider
    {
        if ($this->fieldTypeRegistry->hasFieldType($identifier)) {
            $fieldType = $this->fieldTypeRegistry->getFieldType($identifier);
            if ($fieldType instanceof ChoiceType) {
                return $fieldType->getChoiceProvider();
            }
        }

        return null;
    }
}
