<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Icon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'category.label.label',
                'attr' => [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
