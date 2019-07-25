<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\Type;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\Form\DataTransformer\TextLineFieldTypeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MaskedTextLineFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => $options['mask'],
                'data-input-mask' => $options['mask'],
            ],
        ]);

        $builder->addModelTransformer(new TextLineFieldTypeTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mask' => null,
        ]);
    }
}
