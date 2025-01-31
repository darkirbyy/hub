<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Appli;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppliType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'appli.label.name',
                'attr' => [
                    'placeholder' => 'appli.label.name',
                ],
            ])
            ->add('path', TextType::class, [
                'required' => true,
                'label' => 'appli.label.path',
                'attr' => [
                    'placeholder' => 'appli.label.path',
                ],
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'label',
                'label' => 'appli.label.category',
                'attr' => [
                    'placeholder' => 'appli.label.category',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'appli.label.description',
                'attr' => [
                    'placeholder' => 'appli.label.description',
                ],
            ])
            ->add('linkText', TextType::class, [
                'required' => true,
                'label' => 'appli.label.linkText',
                'attr' => [
                    'placeholder' => 'appli.label.linkText',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appli::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate', // TODO : remove and keep HTML validation ?
            ],
        ]);
    }
}
