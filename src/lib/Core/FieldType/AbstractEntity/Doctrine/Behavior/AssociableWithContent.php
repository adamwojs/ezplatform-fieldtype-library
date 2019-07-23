<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Doctrine\Behavior;

trait AssociableWithContent
{
    /** @var int */
    protected $fieldId;

    /** @var int */
    protected $versionNo;

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function setFieldId(int $fieldId): void
    {
        $this->fieldId = $fieldId;
    }

    public function getVersionNo(): int
    {
        return $this->versionNo;
    }

    public function setVersionNo(int $versionNo): void
    {
        $this->versionNo = $versionNo;
    }

    public function isAssociatedWithContent(): bool
    {
        return true;
    }
}
