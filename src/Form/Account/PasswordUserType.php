<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class PasswordUserType extends DefaultType
{
    private int $passwordMinStrength;

    public function __construct(int $passwordMinStrength)
    {
        $this->passwordMinStrength = $passwordMinStrength >= 0 && $passwordMinStrength <= 4 ? $passwordMinStrength : PasswordStrength::STRENGTH_MEDIUM;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [new NotBlank()];
        if ($this->passwordMinStrength > 0) {
            $constraints[] = new PasswordStrength(['minScore' => $this->passwordMinStrength]);
        }

        $builder->add('plainPassword', RepeatedType::class, [
            'mapped' => false,
            'required' => true,
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'user.label.newPassword',
                'attr' => [
                    'button_action' => 'reveal',
                    'placeholder' => 'user.label.newPassword',
                ],
            ],
            'second_options' => [
                'label' => 'user.label.repeatPassword',
                'attr' => [
                    'button_action' => 'reveal',
                    'placeholder' => 'user.label.repeatPassword',
                ],
            ],
            'constraints' => $constraints,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'data-controller' => 'button-action password-strength',
                'passwordMinStrength' => $this->passwordMinStrength,
            ],
        ]);
    }
}
