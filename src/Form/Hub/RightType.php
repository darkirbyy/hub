<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Right;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RightType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', TextType::class, [
                'required' => true,
                'label' => 'right.label.role',
                'help' => 'right.help.role',
                'button_action' => 'clear',
                'attr' => [
                    'data-collec-field-target' => 'focus',
                    'placeholder' => 'right.label.role',
                ],
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'label' => 'right.label.description',
                'button_action' => 'clear',
                'attr' => [
                    'placeholder' => 'right.label.description',
                ],
                'row_attr' => [
                    'class' => 'flex-grow-1',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Right::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
