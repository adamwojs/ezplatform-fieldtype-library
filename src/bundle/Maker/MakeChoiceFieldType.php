<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeChoiceFieldType extends AbstractFieldTypeMaker
{
    private const WITH_IN_MEMORY_CHOICE_PROVIDER = 'in-memory';

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        parent::configureCommand($command, $inputConfig);

        $command->addOption(
            self::WITH_IN_MEMORY_CHOICE_PROVIDER,
            null,
            InputOption::VALUE_NONE,
            'generate in-memory choice provider'
        );
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $fieldTypeInfo  = $this->getFieldTypeInfoFromInput($input);
        $fieldTypeClass = $this->generateTypeClass($generator, $fieldTypeInfo);

        if ($input->getOption(self::WITH_IN_MEMORY_CHOICE_PROVIDER)) {
            $choiceProviderFactoryClass = $this->generateInMemoryChoiceProviderFactoryClass($generator, $fieldTypeInfo);

            $this->generateConfigForInMemoryChoiceProvider(
                $generator,
                $fieldTypeInfo,
                $fieldTypeClass,
                $choiceProviderFactoryClass
            );
        } else {
            $choiceProviderClass = $this->generateCustomChoiceProviderClass($generator, $fieldTypeInfo);
            $this->generateConfigForCustomChoiceProvider(
                $generator,
                $fieldTypeInfo,
                $fieldTypeClass,
                $choiceProviderClass
            );
        }

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

    private function generateConfigForInMemoryChoiceProvider(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo,
        ClassNameDetails $typeClass,
        ClassNameDetails $choiceProviderFactoryClass
    ): void {
        $generator->generateFile(
            $fieldTypeInfo->getServicesPath($generator),
            $this->getTemplate('services/in_memory.tpl.php'),
            [
                'field_type_identifier' => $fieldTypeInfo->getIdentifier(),
                'field_type_definition' => $fieldTypeInfo->getServiceDefinitionId(),
                'field_type_definition_class' => $typeClass->getFullName(),
                'choice_provider_factory_class' => $choiceProviderFactoryClass->getFullName(),
            ]
        );
    }

    private function generateInMemoryChoiceProviderFactoryClass(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo
    ): ClassNameDetails {
        $class = $generator->createClassNameDetails('ChoiceProviderFactory', $fieldTypeInfo->getNamespace());

        $generator->generateClass(
            $class->getFullName(),
            $this->getTemplate('class/ChoiceProviderFactory.tpl.php')
        );

        return $class;
    }

    private function generateCustomChoiceProviderClass(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo
    ): ClassNameDetails {
        $class = $generator->createClassNameDetails('ChoiceProvider', $fieldTypeInfo->getNamespace());

        $generator->generateClass(
            $class->getFullName(),
            $this->getTemplate('class/ChoiceProvider.tpl.php')
        );

        return $class;
    }

    private function generateConfigForCustomChoiceProvider(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo,
        ClassNameDetails $typeClass,
        ClassNameDetails $choiceProviderClass
    ): void {
        $generator->generateFile(
            $fieldTypeInfo->getServicesPath($generator),
            $this->getTemplate('services/custom.tpl.php'),
            [
                'field_type_identifier' => $fieldTypeInfo->getIdentifier(),
                'field_type_definition' => $fieldTypeInfo->getServiceDefinitionId(),
                'field_type_definition_class' => $typeClass->getFullName(),
                'choice_provider_class' => $choiceProviderClass->getFullName()
            ]
        );
    }

    protected static function getBaseFieldTypeName(): string
    {
        return 'choice';
    }
}
