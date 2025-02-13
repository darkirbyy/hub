<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\Role;
use App\Entity\Hub\Appli;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('key', TextType::class, [
                'required' => true,
                'label' => 'role.label.key',
                'help' => 'role.help.key',
                'attr' => [
                    'clear_button' => true,
                    'placeholder' => 'role.label.key',
                ],
            ])
            ->add('appli', EntityType::class, [
                'required' => true,
                'class' => Appli::class,
                'choice_label' => 'name',
                'label' => 'role.label.appli',
                'attr' => [
                    'placeholder' => 'role.label.appli',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'role.label.description',
                'attr' => [
                    'clear_button' => true,
                    'placeholder' => 'role.label.description',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
                'data-controller' => 'clear-button',
            ],
        ]);
    }
}
