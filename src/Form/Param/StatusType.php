<?php

declare(strict_types=1);

namespace App\Form\Param;

use App\Entity\Param\Icon;
use App\Entity\Param\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'status.label.number',
                'attr' => [
                    'placeholder' => 'status.label.number',
                ],
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'class' => Icon::class,
                'choice_label' => 'label',
                'label' => 'status.label.icon',
                'attr' => [
                    'placeholder' => 'status.label.icon',
                ],
            ])
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'status.label.label',
                'attr' => [
                    'placeholder' => 'status.label.label',
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => 'http',
                'label' => 'status.label.url',
                'attr' => [
                    'placeholder' => 'status.label.url',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
