<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\ChoiceLoader\AutoCompleteChoiceLoader;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type\ChoiceFieldType\AutoCompleteOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AutoCompleteChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $loader = $options['choice_loader'];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            static function (FormEvent $event) use ($loader, $options) {
                $data = $event->getData();

                if ($data === null) {
                    return;
                }

                if ($options['multiple']) {
                    $loader->setPreSelection((array) $data);
                } else {
                    $loader->setPreSelection([$data]);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            static function (FormEvent $event) use ($loader) {
                $loader->setPostSelection((array)$event->getData());
            }
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'auto_complete' => $options['auto_complete'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['auto_complete']);

        $resolver->setDefaults([
            'choice_loader' => static function (Options $options) {
                return new AutoCompleteChoiceLoader($options['auto_complete']->getChoiceProvider());
            },
            'choice_label' => static function (Options $options): callable {
                $provider = $options['auto_complete']->getChoiceProvider();

                return static function ($choice) use ($provider): ?string {
                    return $provider->getLabelForChoice($choice);
                };
            },
            'choice_value' => static function (Options $options): callable {
                $provider = $options['auto_complete']->getChoiceProvider();

                return static function ($choice) use ($provider): ?string {
                    return $choice ? $provider->getValueForChoice($choice) : null;
                };
            },
        ]);

        $resolver->setAllowedTypes('auto_complete', AutoCompleteOptions::class);

        $resolver->setAllowedValues('expanded', false);
        $resolver->setAllowedValues('choice_loader', static function ($value): bool {
            return $value instanceof AutoCompleteChoiceLoader;
        });
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
