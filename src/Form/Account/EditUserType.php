<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
            'required' => true,
            'label' => false,
            'help' => 'user.help.username',
            'row_attr' => [
                'class' => 'mb-0',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
