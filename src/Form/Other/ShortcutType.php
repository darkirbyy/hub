<?php

declare(strict_types=1);

namespace App\Form\Other;

use App\Entity\Other\Shortcut;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortcutType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'shortcut.label.label',
                'button_action' => 'clear',
                'attr' => [
                    'placeholder' => 'shortcut.label.label',
                ],
            ])
            ->add('type', TextType::class, [
                'required' => true,
                'label' => 'shortcut.label.type',
                'attr' => [
                    'placeholder' => 'shortcut.label.type',
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'shortcut.label.number',
                'attr' => [
                    'placeholder' => 'shortcut.label.number',
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => 'http',
                'label' => 'shortcut.label.url',
                'button_action' => 'clear',
                'attr' => [
                    'placeholder' => 'shortcut.label.url',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Shortcut::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
