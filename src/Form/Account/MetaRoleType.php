<?php

declare(strict_types=1);

namespace App\Form\Account;

use App\Entity\Account\MetaRole;
use App\Entity\Hub\Role;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaRoleType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('key', TextType::class, [
                'required' => true,
                'label' => 'metarole.label.key',
                'help' => 'metarole.help.key',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'metarole.label.key',
                ],
            ])
            ->add('roles', EntityType::class, [
                'required' => false,
                'class' => Role::class,
                'choice_label' => 'key',
                'multiple' => true,
                'expanded' => true,
                'label' => 'metarole.label.roles',
                'attr' => [
                    'class' => 'd-flex flex-row justify-content-start flex-wrap row-gap-2 column-gap-4 ms-1',
                ],
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
                ],
                'group_by' => function ($choice, $key, $value) {
                    return (string) $choice->getAppli();
                },
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'metarole.label.description',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'metarole.label.description',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => MetaRole::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
