<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Input\InputInterface;

final class MakeTextLineFieldType extends AbstractFieldTypeMaker
{
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $fieldTypeInfo = $this->getFieldTypeInfoFromInput($input);

        $this->generateServicesConfiguration(
            $generator,
            $fieldTypeInfo,
            $this->generateTypeClass($generator, $fieldTypeInfo),
            $this->generateFormatClass($generator, $fieldTypeInfo)
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    private function generateTypeClass(Generator $generator, FieldTypeInfo $fieldTypeInfo): ClassNameDetails
    {
        $class = $fieldTypeInfo->getClassNameDetails($generator);

        $generator->generateClass(
            $class->getFullName(),
            $this->getTemplate('class/Type.tpl.php'),
            [
                'identifier' => $fieldTypeInfo->getIdentifier(),
            ]
        );

        return $class;
    }

    private function generateFormatClass(Generator $generator, FieldTypeInfo $fieldTypeInfo): ClassNameDetails
    {
        $class = $generator->createClassNameDetails('Format', $fieldTypeInfo->getNamespace());

        $generator->generateClass(
            $class->getFullName(),
            $this->getTemplate('class/Format.tpl.php')
        );

        return $class;
    }

    private function generateServicesConfiguration(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo,
        ClassNameDetails $typeClass,
        ClassNameDetails $formatClass
    ): void {
        $generator->generateFile(
            $fieldTypeInfo->getServicesPath($generator),
            $this->getTemplate('services/services.tpl.php'),
            [
                'field_type_identifier' => $fieldTypeInfo->getIdentifier(),
                'field_type_definition' => $fieldTypeInfo->getServiceDefinitionId(),
                'field_type_definition_class' => $typeClass->getFullName(),
                'format_class' => $formatClass->getFullName(),
            ]
        );
    }

    protected static function getBaseFieldTypeName(): string
    {
        return 'textline';
    }
}
