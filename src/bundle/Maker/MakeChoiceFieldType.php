<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

final class MakeChoiceFieldType extends AbstractMaker
{
    private const TEMPLATE_DIR = __DIR__ . '/../Resources/skeleton/choice-field-type/';

    private const FIELD_TYPE_NAME = 'name';
    private const FIELD_TYPE_IDENTIFIER = 'identifier';
    private const WITH_IN_MEMORY_CHOICE_PROVIDER = 'in-memory';

    public static function getCommandName(): string
    {
        return 'make:ezplatform:choice-field-type';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->setDescription('Creates a new eZ Platform choice field type');

        $command->addArgument(
            self::FIELD_TYPE_NAME,
            InputArgument::REQUIRED,
            'field type name e.g. ColorChoice'
        );

        $command->addOption(
            self::FIELD_TYPE_IDENTIFIER,
            null,
            InputArgument::OPTIONAL,
            'field type identifier e.g. ezcolorchoice'
        );

        $command->addOption(
            self::WITH_IN_MEMORY_CHOICE_PROVIDER,
            null,
            InputOption::VALUE_NONE,
            'generate in-memory choice provider'
        );
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (!$input->getArgument(self::FIELD_TYPE_NAME)) {
            $argument = $command->getDefinition()->getArgument(self::FIELD_TYPE_NAME);

            $question = new Question($argument->getDescription());
            $question->setValidator(function (?string $value): string {
                if (null === $value || '' === $value) {
                    throw new RuntimeCommandException('This value cannot be blank.');
                }

                return $value;
            });

            $input->setArgument(self::FIELD_TYPE_NAME, $io->askQuestion($question));
        }

        if (!$input->getOption(self::FIELD_TYPE_IDENTIFIER)) {
            $input->setOption(self::FIELD_TYPE_IDENTIFIER, strtolower($input->getArgument(self::FIELD_TYPE_NAME)));
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        /* Nothing to configure */
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $typeIdentifier = $input->getOption(self::FIELD_TYPE_IDENTIFIER);
        $namespace = 'FieldType\\' . $input->getArgument(self::FIELD_TYPE_NAME) . '\\';

        $typeClass = $generator->createClassNameDetails('Type', $namespace);

        $this->generateTypeClass($generator, $typeIdentifier, $typeClass);
        if ($input->getOption(self::WITH_IN_MEMORY_CHOICE_PROVIDER)) {
            $choiceProviderFactoryClass = $generator->createClassNameDetails('ChoiceProviderFactory', $namespace);

            $this->generateInMemoryChoiceProviderFactoryClass($generator, $choiceProviderFactoryClass);
            $this->generateConfigForInMemoryChoiceProvider(
                $generator,
                $typeIdentifier,
                $typeClass,
                $choiceProviderFactoryClass
            );
        } else {
            $choiceProviderClass = $generator->createClassNameDetails('ChoiceProvider', $namespace);

            $this->generateCustomChoiceProviderClass($generator, $choiceProviderClass);
            $this->generateConfigForCustomChoiceProvider(
                $generator,
                $typeIdentifier,
                $typeClass,
                $choiceProviderClass
            );
        }

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    private function generateTypeClass(Generator $generator, string $identifier, ClassNameDetails $typeClass): void
    {
        $generator->generateClass(
            $typeClass->getFullName(),
            self::TEMPLATE_DIR . 'class/Type.tpl.php',
            [
                'identifier' => $identifier,
            ]
        );
    }

    private function generateConfigForInMemoryChoiceProvider(
        Generator $generator,
        string $identifier,
        ClassNameDetails $typeClass,
        ClassNameDetails $choiceProviderFactoryClass
    ): void
    {
        $generator->generateFile(
            $this->getServicesConfigPath($generator, $identifier),
            self::TEMPLATE_DIR . '/services/in_memory.tpl.php',
            [
                'field_type_identifier' => $identifier,
                'field_type_definition' => 'app.field_type.' . $identifier,
                'field_type_definition_class' => $typeClass->getFullName(),
                'choice_provider_factory_class' => $choiceProviderFactoryClass->getFullName(),
            ]
        );
    }

    private function generateInMemoryChoiceProviderFactoryClass(Generator $generator, ClassNameDetails $factoryClass): void
    {
        $generator->generateClass(
            $factoryClass->getFullName(),
            self::TEMPLATE_DIR . 'class/ChoiceProviderFactory.tpl.php'
        );
    }

    private function generateCustomChoiceProviderClass(Generator $generator, ClassNameDetails $providerClass): void
    {
        $generator->generateClass(
            $providerClass->getFullName(),
            self::TEMPLATE_DIR . 'class/ChoiceProvider.tpl.php'
        );
    }

    private function generateConfigForCustomChoiceProvider(
        Generator $generator,
        string $identifier,
        ClassNameDetails $typeClass,
        ClassNameDetails $choiceProviderClass
    ): void
    {
        $generator->generateFile(
            $this->getServicesConfigPath($generator, $identifier),
            self::TEMPLATE_DIR . '/config/services/custom.tpl.php',
            [
                'field_type_identifier' => $identifier,
                'field_type_definition' => 'app.field_type.' . $identifier,
                'field_type_definition_class' => $typeClass->getFullName(),
                'choice_provider_class' => $choiceProviderClass->getFullName()
            ]
        );
    }

    private function getServicesConfigPath(Generator $generator, string $identifier): string
    {
        return $generator->getRootDirectory() . '/config/services/fieldtype/' . $identifier . '.yaml';
    }
}
