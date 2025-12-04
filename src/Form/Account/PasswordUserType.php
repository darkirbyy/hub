<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use App\Enum\PasswordStrengthEnum;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength as PasswordConstraint;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordUserType extends DefaultType
{
    public function __construct(protected bool $htmlValidation, private PasswordStrengthEnum $passwordStrength, private TranslatorInterface $trans)
    {
        parent::__construct($htmlValidation);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [new NotBlank()];
        if (PasswordStrengthEnum::VERY_WEAK !== $this->passwordStrength) {
            $constraints[] = new PasswordConstraint(minScore: $this->passwordStrength->toMinScore());
        }

        $builder->add('plainPassword', RepeatedType::class, [
            'mapped' => false,
            'required' => true,
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'user.label.newPassword',
                'button_action' => 'reveal',
                'attr' => [
                    'placeholder' => 'user.label.newPassword',
                    'data-password-strength-target' => 'input',
                ],
            ],
            'second_options' => [
                'label' => 'user.label.repeatPassword',
                'button_action' => 'reveal',
                'attr' => [
                    'placeholder' => 'user.label.repeatPassword',
                ],
            ],
            'constraints' => $constraints,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $strengthTexts = json_encode(array_map(fn (PasswordStrengthEnum $p) => $p->trans($this->trans), PasswordStrengthEnum::cases()));
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'data-controller' => 'button-action password-strength',
                'data-password-strength-strength-texts-value' => $strengthTexts,
            ],
        ]);
    }
}
