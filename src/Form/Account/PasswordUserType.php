<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('plainPassword', RepeatedType::class, [
            'mapped' => false,
            'required' => true,
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'button_action' => 'reveal',
                ],
            ],
            'first_options' => [
                'label' => 'user.label.newPassword',
                'attr' => [
                    'placeholder' => 'user.label.newPassword',
                ],
            ],
            'second_options' => [
                'label' => 'user.label.repeatPassword',
                'attr' => [
                    'placeholder' => 'user.label.repeatPassword',
                ],
            ],
            'constraints' => [new NotBlank(), new PasswordStrength(['minScore' => PasswordStrength::STRENGTH_MEDIUM])],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
