<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AvatarUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('imageFile', VichImageType::class, [
            'required' => false,
            'label' => false,
            'help' => 'user.help.image',
            'help_html' => true,
            'allow_delete' => true,
            'download_uri' => false,
            'image_uri' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
                'data-controller' => 'form-redirect load-popover',
            ],
        ]);
    }
}
