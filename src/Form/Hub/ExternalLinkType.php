<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\ExternalLink;
use App\Entity\Hub\Icon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('icon', EntityType::class, [
                'required' => true,
                'class' => Icon::class,
                'choice_label' => 'label',
                'label' => 'externalLink.label.icon',
                'attr' => [
                    'placeholder' => 'externalLink.label.icon',
                ],
            ])
            ->add('text', TextType::class, [
                'required' => false,
                'label' => 'externalLink.label.text',
                'attr' => [
                    'placeholder' => 'externalLink.label.text',
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => 'http',
                'label' => 'externalLink.label.url',
                'row_attr' => [
                    'class' => 'flex-grow-1',
                ],
                'attr' => [
                    'placeholder' => 'externalLink.label.url',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExternalLink::class,
            'translation_domain' => 'validators',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
