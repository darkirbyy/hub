<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnectUserType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'user.label.username',
                'attr' => [
                    'placeholder' => 'user.label.username',
                    'autocomplete' => 'username',
                    'autofocus' => true,
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'user.label.password',
                'button_action' => 'reveal',
                'attr' => [
                    'placeholder' => 'user.label.password',
                    'autocomplete' => 'current-password',
                ],
            ])
            ->add('rememberMe', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'user.label.rememberMe',
                'attr' => [
                    'placeholder' => 'user.label.rememberMe',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'hub/login',
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
