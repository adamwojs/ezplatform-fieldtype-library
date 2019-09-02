<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

final class FieldTypeInfo
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $namespace;

    public function __construct(string $identifier, string $namespace)
    {
        $this->identifier = $identifier;
        $this->namespace = $namespace;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getClassNameDetails(Generator $generator): ClassNameDetails
    {
        return $generator->createClassNameDetails('Type', $this->namespace);
    }

    public function getServiceDefinitionId(): string
    {
        return 'app.field_type.' . $this->getIdentifier();
    }

    /**
     * Returns target path for DIC configuration.
     *
     * @param \Symfony\Bundle\MakerBundle\Generator $generator
     *
     * @return string
     */
    public function getServicesPath(Generator $generator): string
    {
        return $generator->getRootDirectory() . '/config/services/fieldtype/' . $this->identifier . '.yaml';
    }
}
