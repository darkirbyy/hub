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
    private int $passwordStrength;

    public function __construct(int $passwordStrength)
    {
        $this->passwordStrength = $passwordStrength;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [new NotBlank()];
        if ($this->passwordStrength > 0) {
            $minScore = $this->passwordStrength >= 1 && $this->passwordStrength <= 4 ? $this->passwordStrength : PasswordStrength::STRENGTH_MEDIUM;
            $constraints[] = new PasswordStrength(['minScore' => $minScore]);
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
