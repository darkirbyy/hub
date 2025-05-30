<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Role;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('key', TextType::class, [
                'required' => true,
                'label' => 'role.label.key',
                'help' => 'role.help.key',
                'attr' => [
                    'data-collec-field-target' => 'focus',
                    'button_action' => 'clear',
                    'placeholder' => 'role.label.key',
                ],
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'label' => 'role.label.description',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'role.label.description',
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
            'data_class' => Role::class,
            'attr' => [],
        ]);
    }
}
