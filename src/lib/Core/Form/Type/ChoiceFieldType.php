<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer\ChoiceFieldTypeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChoiceFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider $choiceProvider */
        $choiceProvider = $options['choice_provider'];

        $builder->add('selection', $options['choice_widget'], [
            'label' => false,
            'multiple' => $options['multiple'],
            'choice_loader' => new CallbackChoiceLoader(function () use ($choiceProvider) {
                return $choiceProvider->getChoices(new ChoiceCriteria());
            }),
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
        $resolver->setDefault('choice_widget', ChoiceType::class);
        $resolver->setRequired('multiple');
        $resolver->setRequired('choice_provider');
    }
}
