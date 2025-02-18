<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Icon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'icon.label.label',
                'attr' => [
                    'button_action' => 'clear',
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
        $resolver->setDefaults([
            'data_class' => Icon::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
