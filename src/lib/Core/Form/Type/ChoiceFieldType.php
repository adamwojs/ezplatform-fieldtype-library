<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer\ChoiceFieldTypeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChoiceFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\Choice\ChoiceProvider $choiceProvider */
        $choiceProvider = $options['choice_provider'];

        $builder->add('selection', ChoiceType::class, [
            'label' => false,
            'multiple' => $options['multiple'],
            'choices' => $choiceProvider->getAllChoices(),
            'choice_label' => function ($choice) use ($choiceProvider) {
                return $choiceProvider->getLabelForChoice($choice);
            },
            'choice_value' => function ($choice) use ($choiceProvider) {
                return $choice ? $choiceProvider->getValueForChoice($choice) : null;
            },
        ]);

        $builder->addModelTransformer(new ChoiceFieldTypeTransformer($options['multiple']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('multiple');
        $resolver->setRequired('choice_provider');
    }
}
