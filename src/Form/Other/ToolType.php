<?php

declare(strict_types=1);

namespace App\Form\Other;

use App\Entity\Hub\Icon;
use App\Entity\Other\Tool;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class ToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'tool.label.number',
                'attr' => [
                    'placeholder' => 'tool.label.number',
                ],
            ])
            ->add('type', TextType::class, [
                'required' => true,
                'label' => 'tool.label.type',
                'attr' => [
                    'placeholder' => 'tool.label.type',
                ],
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'class' => Icon::class,
                'choice_label' => 'label',
                'placeholder' => new TranslatableMessage('form.other.none'),
                'label' => 'tool.label.icon',
                'attr' => [
                    'placeholder' => 'tool.label.icon',
                ],
            ])
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'tool.label.label',
                'attr' => [
                    'placeholder' => 'tool.label.label',
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => 'http',
                'label' => 'tool.label.url',
                'attr' => [
                    'placeholder' => 'tool.label.url',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
