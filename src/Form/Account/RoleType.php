<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\Role;
use App\Entity\Hub\Appli;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                    'button_action' => 'clear',
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
                    'button_action' => 'clear',
                    'placeholder' => 'role.label.description',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Role::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
