<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractFieldTypeMaker extends AbstractMaker
{
    protected const FIELD_TYPE_NAME = 'name';
    protected const FIELD_TYPE_IDENTIFIER = 'identifier';

    public static function getCommandName(): string
    {
        return 'make:ezplatform:' . static::getBaseFieldTypeName() . '-field-type';
    }

    /**
     * Return base field type name.
     *
     * @return string
     */
    abstract protected static function getBaseFieldTypeName(): string;

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command->setDescription(sprintf('Creates a new eZ Platform %s field type', static::getBaseFieldTypeName()));

        $command->addArgument(
            self::FIELD_TYPE_NAME,
            InputArgument::REQUIRED,
            'field type name e.g. Color'
        );

        $command->addOption(
            self::FIELD_TYPE_IDENTIFIER,
            null,
            InputArgument::OPTIONAL,
            'field type identifier e.g. ezcolor'
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

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        /* Nothing to configure */
    }

    protected function getFieldTypeInfoFromInput(InputInterface $input): FieldTypeInfo
    {
        $identifier = $input->getOption(self::FIELD_TYPE_IDENTIFIER);
        $namespace = 'FieldType\\' . $input->getArgument(self::FIELD_TYPE_NAME) . '\\';

        return new FieldTypeInfo($identifier, $namespace);
    }

    protected function getTemplate(string $name): string
    {
        return __DIR__ . '/../Resources/skeleton/' . static::getBaseFieldTypeName() . '-field-type/' . $name;
    }
}
