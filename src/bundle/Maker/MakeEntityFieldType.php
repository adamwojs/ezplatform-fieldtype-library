<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

final class MakeEntityFieldType extends AbstractFieldTypeMaker
{
    private const ENTITY_NAME = 'entity';

    private $doctrineHelper;

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    protected static function getBaseFieldTypeName(): string
    {
        return 'entity';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->addArgument(
            self::ENTITY_NAME,
            InputArgument::REQUIRED,
            'The class name of the entity to store field type value'
        );

        $inputConfig->setArgumentAsNonInteractive(self::FIELD_TYPE_NAME);

        parent::configureCommand($command, $inputConfig);
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (null === $input->getArgument(self::ENTITY_NAME)) {
            $argument = $command->getDefinition()->getArgument(self::ENTITY_NAME);

            $question = new Question($argument->getDescription());
            $question->setValidator(function (?string $value): string {
                if (null === $value || '' === $value) {
                    throw new RuntimeCommandException('This value cannot be blank.');
                }

                return $value;
            });

            $input->setArgument(self::ENTITY_NAME, $io->askQuestion($question));
        }

        if (null === $input->getArgument(self::FIELD_TYPE_NAME)) {
            $input->setArgument(self::FIELD_TYPE_NAME, $input->getArgument(self::ENTITY_NAME));
        }

        parent::interact($input, $io, $command);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entityClass = $input->getArgument(self::ENTITY_NAME);
        if (strpos($entityClass, '\\') === false) {
            $entityClass = 'App\\Entity\\' . $entityClass;
        }

        $fieldTypeInfo = $this->getFieldTypeInfoFromInput($input);
        $fieldTypeClass = $this->generateTypeClass($generator, $fieldTypeInfo, $entityClass);

        $this->generateServicesConfiguration(
            $generator,
            $fieldTypeInfo,
            $fieldTypeClass,
            $entityClass
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    private function generateTypeClass(Generator $generator, FieldTypeInfo $fieldTypeInfo, string $entity): ClassNameDetails
    {
        $class = $fieldTypeInfo->getClassNameDetails($generator);

        $generator->generateClass(
            $class->getFullName(),
            $this->getTemplate('class/Type.tpl.php'),
            [
                'identifier' => $fieldTypeInfo->getIdentifier(),
                'entity_full_name' => $entity,
                'entity_short_name' => Str::getShortClassName($entity),
            ]
        );

        return $class;
    }

    private function generateServicesConfiguration(
        Generator $generator,
        FieldTypeInfo $fieldTypeInfo,
        ClassNameDetails $typeClass,
        string $entityClass
    ): void {
        $generator->generateFile(
            $fieldTypeInfo->getServicesPath($generator),
            $this->getTemplate('services/services.tpl.php'),
            [
                'field_type_identifier' => $fieldTypeInfo->getIdentifier(),
                'field_type_definition' => $fieldTypeInfo->getServiceDefinitionId(),
                'field_type_definition_class' => $typeClass->getFullName(),
                'entity_full_name' => $entityClass,
            ]
        );
    }
}
