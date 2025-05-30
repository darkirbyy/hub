<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use App\Form\DefaultType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditUserType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'user.label.username',
                'help' => 'user.help.username',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'user.label.username',
                ],
            ])
            ->add('metaAdmin', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'enum.choices.yes' => true,
                    'enum.choices.no' => false,
                ],
                'choice_translation_domain' => 'messages',
                'label' => 'user.label.metaAdmin',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'expanded' => true,
                'multiple' => false,
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
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
                    'image_class' => 'app-avatar-img app-avatar-container-lg',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
