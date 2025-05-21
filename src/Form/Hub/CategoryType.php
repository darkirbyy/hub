<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Category;
use App\Entity\Hub\Icon;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'category.label.label',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'category.label.label',
                ],
            ])
            ->add('icon', EntityType::class, [
                'required' => true,
                'class' => Icon::class,
                'choice_label' => 'label',
                'label' => 'category.label.icon',
                'attr' => [
                    'placeholder' => 'category.label.icon',
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'category.label.number',
                'attr' => [
                    'placeholder' => 'category.label.number',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
