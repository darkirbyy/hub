<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Category;
use App\Entity\Hub\Icon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                    'button_action' => 'clear',
                    'placeholder' => 'category.label.label',
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'category.label.number',
                'attr' => [
                    'placeholder' => 'category.label.number',
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
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
