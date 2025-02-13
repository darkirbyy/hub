<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'user.label.username',
                'help' => 'user.help.username',
                'attr' => [
                    'clear_button' => true,
                    'placeholder' => 'user.label.username',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'user.label.imageFile',
                'help' => 'user.help.image',
                'help_html' => true,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
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
                'data-controller' => 'clear-button',
            ],
        ]);
    }
}
