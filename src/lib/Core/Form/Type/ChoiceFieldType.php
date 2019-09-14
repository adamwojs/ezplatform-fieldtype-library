<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\AbstractChoice\Value;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\ChoiceLoader\ChoiceFieldTypeChoiceLoader;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer\ChoiceFieldTypeTransformer;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType\AutoCompleteOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChoiceFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider $provider */
        $provider = $options['auto_complete']->getChoiceProvider();
        $loader = new ChoiceFieldTypeChoiceLoader($provider);

        $builder->add('selection', AutoCompleteChoiceType::class, [
            'label' => false,
            'multiple' => $options['multiple'],
            'choice_loader' => $loader,
            'choice_label' => function ($choice) use ($provider) {
                return $provider->getLabelForChoice($choice);
            },
            'choice_value' => function ($choice) use ($provider) {
                return $choice ? $provider->getValueForChoice($choice) : null;
            },
            'attr' => [
                'data-autocomplete-provider' => $options['auto_complete']->getIdentifier(),
            ],
        ]);

        $builder->addModelTransformer(new ChoiceFieldTypeTransformer($options['multiple']));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($loader) {
                $data = $event->getData();
                if ($data instanceof Value) {
                    $loader->setSelection($data->getSelection());
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($loader) {
                $data = $event->getData();

                if (isset($data['selection'])) {
                    $selection = $data['selection'];
                    if (!is_array($selection)) {
                        $selection = [$selection];
                    }

                    $loader->setSelection($selection);
                }
            }
        );
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
