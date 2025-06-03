<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use App\Entity\Hub\Right;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('rights', EntityType::class, [
                'required' => false,
                'class' => Right::class,
                'choice_label' => fn (Right $right) => $right->__toString(),
                'multiple' => true,
                'expanded' => true,
                'label' => 'user.label.rights',
                'attr' => [
                    'class' => 'd-flex flex-column justify-content-start flex-nowrap gap-2',
                ],
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
                ],
                'group_by' => function ($choice, $key, $value) {
                    return (string) $choice->getAppli();
                },
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
