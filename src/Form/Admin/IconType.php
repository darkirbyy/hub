<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Icon;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'icon.label.label',
                'button_action' => 'clear',
                'attr' => [
                    'placeholder' => 'icon.label.label',
                ],
            ])
            ->add('faClass', TextType::class, [
                'required' => true,
                'label' => 'icon.label.faClass',
                'help' => 'icon.help.faClass',
                'help_html' => true,
                'attr' => [
                    'placeholder' => 'icon.label.faClass',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Icon::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
