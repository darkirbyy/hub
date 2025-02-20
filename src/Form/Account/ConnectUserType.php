<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnectUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'user.label.username',
                'attr' => [
                    // 'button_action' => 'clear',
                    'placeholder' => 'user.label.username',
                    'autocomplete' => 'username',
                    'autofocus' => true,
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'user.label.password',
                'attr' => [
                    'button_action' => 'reveal',
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
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'validators',
            'csrf_token_id' => 'authenticate',
            'attr' => [
                'novalidate' => 'novalidate',
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
