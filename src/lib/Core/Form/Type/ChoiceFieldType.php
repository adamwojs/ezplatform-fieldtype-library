<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer\ChoiceFieldTypeTransformer;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType\AutoCompleteOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChoiceFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('selection', AutoCompleteChoiceType::class, [
            'label' => false,
            'multiple' => $options['multiple'],
            'auto_complete' => $options['auto_complete'],
        ]);

        $builder->addModelTransformer(new ChoiceFieldTypeTransformer($options['multiple']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => false,
        ]);

        $resolver->setRequired('auto_complete');
        $resolver->setAllowedTypes('auto_complete', AutoCompleteOptions::class);
    }
}
