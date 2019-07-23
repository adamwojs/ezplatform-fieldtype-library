<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Gateway;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Value;
use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\SPI\FieldType\GatewayBasedStorage;
use eZ\Publish\SPI\FieldType\StorageGateway;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

final class Storage extends GatewayBasedStorage
{
    use LoggerAwareTrait;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

    /** @var string */
    private $entityClass;

    /**
     * @param \eZ\Publish\SPI\FieldType\StorageGateway $gateway
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param string $entityClass
     */
    public function __construct(StorageGateway $gateway, EntityManagerInterface $em, string $entityClass)
    {
        parent::__construct($gateway);

        $this->em = $em;
        $this->entityClass = $entityClass;
        $this->logger = new NullLogger();
    }

    public function storeFieldData(VersionInfo $versionInfo, Field $field, array $context): bool
    {
        $entity = $field->value->externalData;
        if (!$entity) {
            return false;
        }

        $existingEntity = $this->findEntity($versionInfo, $field->id);
        if ($existingEntity !== null) {
            $this->em->remove($existingEntity);
        }

        if (!$this->em->contains($entity)) {
            $this->setFieldAssociation($versionInfo, $field, $entity);
            $this->em->persist($entity);
        }

        $this->em->flush();

        return true;
    }

    public function getFieldData(VersionInfo $versionInfo, Field $field, array $context): void
    {
        $entity = $this->findEntity($versionInfo, $field->id);
        if ($entity === null) {
            $this->logger->error("Entity {$this->entityClass} with field id '{$field->id}' and version '{$field->versionNo}' not found");
        }

        $field->value->externalData = $entity;
    }

    public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds, array $context): void
    {
        foreach ($fieldIds as $fieldId) {
            $entity = $this->findEntity($versionInfo, $fieldId);
            if ($entity !== null) {
                $this->em->remove($entity);
            }
        }

        $this->em->flush();
    }

    public function hasFieldData(): bool
    {
        return true;
    }

    public function getIndexData(VersionInfo $versionInfo, Field $field, array $context): bool
    {
        return false;
    }

    public function copyLegacyField(VersionInfo $versionInfo, Field $field, Field $originalField, array $context): bool
    {
        if ($field->value->externalData) {
            $entity = clone $field->value->externalData;

            $this->setFieldAssociation($versionInfo, $field, $entity);
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        }

        return false;
    }

    protected function findEntity(VersionInfo $versionInfo, int $fieldId)
    {
        return $this->em->getRepository($this->entityClass)->findOneBy([
            'fieldId' => $fieldId,
            'versionNo' => $versionInfo->versionNo,
        ]);
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractEntity\Value $entity
     */
    protected function setFieldAssociation(
        VersionInfo $versionInfo,
        Field $field,
        Value $entity
    ): void {
        $entity->setFieldId($field->id);
        $entity->setVersionNo($versionInfo->versionNo);
    }
}
